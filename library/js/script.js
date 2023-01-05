$(document).ready(function(){
        // Detail Item (Isi Body Modal)
        $('.detail').on('click', function(){
                const id = $(this).data('id');
                const hal = $(this).data('hal');
                const url = "http://gateway.marvel.com/v1/public/";
                const partNeed = "?ts=3&apikey=fb51fa3f134a5857db5cdccfc05d3c1b&hash=5b8f571806a08368cc206dbba08ac727";

                console.log(url + hal + partNeed + "&id=" + id);

                $.getJSON(url + hal + partNeed + "&id=" + id, function(data){
                        let item = data.data.results[0];
                        if(hal == "comics"){
                                $('.modal-body').html(`
                                <img class="card-img-top border border-4 border-secondary mb-2" src="`+item.thumbnail.path+`.`+item.thumbnail.extension+`">
                                <h5>`+item.title+`</h5>
                                <p>`+ item.description +`</p>
                                `);
                        } else {
                                ('.modal-body').html(`
                                <img class="card-img-top border border-4 border-secondary mb-2" src="`+item.thumbnail.path+`.`+item.thumbnail.extension+`">
                                <h5>`+item.name+`</h5>
                                <p>`+ item.description +`</p>
                                `);
                        }
                });
        });
});