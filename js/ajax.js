$.ajax({
    type: "GET",
    url: "../geoJson/SHClosures.geojson",
    dataType: 'json',
    success: function (data) {
        
        console.log("####### SHClosures.geojson info below ##########");

        console.log(data);

        var myObject = data;
        var obj = Object.keys(myObject).length;
        console.log(obj);

    },
    error: function (request, status, error) {
        $("#SHErrorsShow").html("The SHClosures geoJson data file has been mislpaced");
        console.log(request);
        console.log(status);
        console.log(error);
    }
});


$.ajax({
    type: "GET",
    url: "../geoJson/chchTraffic.geojson",
    dataType: 'json',
    success: function (data) {
        
        console.log("####### chchTraffic.geojson info below ##########");

        console.log(data);

        var myObject = data;
        var obj = Object.keys(myObject).length;
        console.log(obj);

    },
    error: function (request, status, error) {
        $("#CHCHErrorsShow").html("The chchTraffic geoJson data file has been mislpaced");
        console.log(request);
        console.log(status);
        console.log(error);
    }
});