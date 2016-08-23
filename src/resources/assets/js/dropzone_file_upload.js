/**
 * Created by daltongibbs on 8/22/16.
 */

$(function(){

    $(document).on('dragenter', function(){
        $("#main").prepend("<div id='upload_hotspot'></div>");

        var dropzone = $("#upload_hotspot").dropzone({
            url: "/upload/submit",
            method: "put",
            paramName: "file",
            uploadMultiple: true,
            autoProcessQueue: true
        });

        dropzone.on('drop', function(file){
            console.log(file);
        });
    });

    $(document).on('dragleave', "#upload_hotspot", function(){
        $("#upload_hotspot").remove();
    });

});