<?php
session_start();
$conn = mysqli_connect("sql206.epizy.com", "epiz_32257807", "riBHOzXcB01KKVW", "epiz_32257807_RMarvel");

function signin($data){
    global $conn;
    $username = strtolower(stripslashes($data["username"]));
    $password = mysqli_real_escape_string($conn, $data["password"]);
    $password1 = mysqli_real_escape_string($conn, $data["password1"]);

    $username_check = mysqli_query($conn, "SELECT username FROM user WHERE username = '$username'");
    if(mysqli_fetch_assoc($username_check)){
        echo "
        <script>
            alert('Username Telah Terdaftar!');
        </script>
        ";
        return;
    }

    if($password != $password1){
        echo "
        <script>
            alert('Password Tidak Sesuai!');
        </script>
        ";
        return;
    }

    $password = password_hash($password, PASSWORD_DEFAULT);

    mysqli_query($conn, "INSERT INTO user VALUES('', '$username', '$password')");
    echo "
        <script>
            alert('Sign-In Success!');
        </script>
    ";
    $_SESSION["login"] = true;
    
    return mysqli_affected_rows($conn);
}

function login($data){
    global $conn;
    $username = $data["username"];
    $password = $data["password"];

    $check = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");
    if(mysqli_num_rows($check)){
        $row = mysqli_fetch_assoc($check);
        if(password_verify($password, $row["password"])) {
            // set cookie
            if(isset($_POST["cookie"])){
                setcookie('id', $row["id"], time() + 100000);
                setcookie('key', hash('sha256', $row["username"]), time() + 100000);
            }

            // set session
            $_SESSION["login"] = true;
            header("Location: index.php?hal=comics");
        }
        else{
            echo "
                <script>
                    alert('Password Salah!');
                </script>
            ";
        };
    } else{
        echo "
            <script>
                alert('Username Not Found!');
            </script>
        ";
    };
};


































?>