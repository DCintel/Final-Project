$(function(){
    
    var id = 5000;
    
    var parameters = {
        id: id
    };
    
    while(id != 6414){
        $.getJSON("gamesradarscrape.php", parameters)
        .done(function(data, textStatus, jqXHR){
            console.log(data);
        })
        .fail(function(data, textStatus, jqXHR){
            console.log(textStatus);
        });
        
        id+=1;
        parameters.id = id;
        console.log(parameters.id);
        
    }
    //while(id !=6413)
    //{
     //   $.getJSON("fullscrapeV4.php", parameters)
     //   .done(function(data, textStatus, jqXHR){
     //      console.log(data); 
     //   })
     //   .fail(function(data, textStatus, jqXHR){
    //        console.log(textStatus);
    //    });
        
    //    id += 1;
    //    parameters.id = id;
    //    console.log(parameters.id);
    //}
})