$(function () {

    $('#upload-main').uploadifive({
        'auto'             : false,
        'formData'         : {
            'files': $('#filename-main').val()
        },
        'method'   : 'post',
        'multi'         : true,
        'queueID'          : 'queue-main',
        'uploadScript'     : '/cms-ir/team/upload-main',
        'onUploadComplete' : function(file, data) {
            $('#filename-main').val(data);
            if($('#filename-main').val().length > 0) {
                $('.files-main img').remove();
                $('.files-main').append('<div class="deletePhoto_main">  <i class="fa fa-times" data-toggle="tooltip" title="Usuń zdjęcie"></i> <img src="/files/team/'+data+'" class="thumb" /> </div>')
            }

            $('.deletePhoto_main i').on('click', function () {
                var id = 0;
                var fullPathToImage = $(this).next().attr('src');

                if($(this).parent().is("[id]"))
                {
                    id = $(this).parent().attr('id');
                }
                $('#filename-main').val('');
                $cache = $(this);
                $.ajax({
                    type: "POST",
                    url: "/cms-ir/team/delete-photo-main",
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

    if($('#filename-main').val().length > 0) {
        var filename = $('#filename-main').val();
        $('.files-main').append('<div class="deletePhoto_main">  <i class="fa fa-times" data-toggle="tooltip" title="Usuń zdjęcie"></i> <img src="/files/team/'+filename+'" class="thumb" /> </div>')
    }

    $('.deletePhoto_main i').on('click', function () {
        var id = 0;
        var fullPathToImage = $(this).next().attr('src');

        if($(this).parent().is("[id]"))
        {
            id = $(this).parent().attr('id');
        }

        $('#filename-main').val('');
        $cache = $(this);
        $.ajax({
            type: "POST",
            url: "/cms-ir/team/delete-photo-main",
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

});