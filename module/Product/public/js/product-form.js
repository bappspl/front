$(function () {
    $('#upload').uploadifive({
        'auto'             : false,
        'formData'         : {
            'files': $('#filename').val()
        },
        'method'   : 'post',
        'multi'         : true,
        'queueID'          : 'queue',
        'uploadScript'     : '/cms-ir/product/upload',
        'onUploadComplete' : function(file, data) {
            $('#filename').val(data);
            if($('#filename').val().length > 0) {
                $('.files').append('<div class="deletePhoto">  <i class="fa fa-times" data-toggle="tooltip" title="Usuń zdjęcie"></i> <img src="/temp_files/product/'+data+'" class="thumb" /> </div>')
            }

            $('.deletePhoto i').on('click', function () {

                var id = 0;
                var fullPathToImage = $(this).next().attr('src');

                if($(this).parent().is("[id]"))
                {
                    id = $(this).parent().attr('id');
                }
                $cache = $(this);
                $.ajax({
                    type: "POST",
                    url: "/cms-ir/product/delete-photo",
                    dataType : 'json',
                    data: {
                        id: id,
                        filePath: fullPathToImage
                    },
                    success: function(json)
                    {
                        $cache.parent().remove();
                    }
                });

            });
        }
    });

    $('.deletePhoto i').on('click', function () {

        var id = 0;
        var fullPathToImage = $(this).next().attr('src');

        if($(this).parent().is("[id]"))
        {
            id = $(this).parent().attr('id');
        }
        $cache = $(this);
        $.ajax({
            type: "POST",
            url: "/cms-ir/product/delete-photo",
            dataType : 'json',
            data: {
                id: id,
                filePath: fullPathToImage
            },
            success: function(json)
            {
                $cache.parent().remove();
            }
        });

    });

    $(".select2").select2({
        tags: true
    })

});