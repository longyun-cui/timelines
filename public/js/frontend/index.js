jQuery( function ($) {

    $(".user-logout").on("click",function() {
        location.href = "/logout";
    });

    $(".admin-logout").on("click",function() {
        location.href = "/admin/logout";
    });

    $('.course-option').on('click', '.show-menu', function () {
        var course_option = $(this).parents('.course-option');
        course_option.find('.menu-container').show();
        $(this).removeClass('show-menu').addClass('hide-menu');
        $(this).html('隐藏目录');
    });

    $('.course-option').on('click', '.hide-menu', function () {
        var course_option = $(this).parents('.course-option');
        course_option.find('.menu-container').hide();
        $(this).removeClass('hide-menu').addClass('show-menu');
        $(this).html('查看目录');
    });




    // 收藏
    $(".item-option").off("click",".collect-this").on('click', ".collect-this", function() {
        var that = $(this);
        var item_option = $(this).parents('.item-option');

        $.post(
            "/item/collect/save",
            {
                _token: $('meta[name="_token"]').attr('content'),
                course_id: item_option.attr('data-id'),
                content_id: 0,
                type: 1
            },
            function(data){
                if(!data.success) layer.msg(data.msg);
                else
                {
                    layer.msg("收藏成功");
                    item_option.html(data.data.html);
                }
            },
            'json'
        );
    });
    // 取消收藏
    $(".item-option").off("click",".collect-this-cancel").on('click', ".collect-this-cancel", function() {
        var that = $(this);
        var item_option = $(this).parents('.item-option');

        layer.msg('取消"收藏"？', {
            time: 0
            ,btn: ['确定', '取消']
            ,yes: function(index){
                $.post(
                    "/item/collect/cancel",
                    {
                        _token: $('meta[name="_token"]').attr('content'),
                        course_id: item_option.attr('data-id'),
                        content_id: 0,
                        type: 1
                    },
                    function(data){
                        if(!data.success) layer.msg(data.msg);
                        else
                        {
                            item_option.html(data.data.html);
                            layer.closeAll();
                            // var index = parent.layer.getFrameIndex(window.name);
                            // parent.layer.close(index);
                        }
                    },
                    'json'
                );
            }
        });
    });




    // 点赞
    $(".item-option").off("click",".favor-this").on('click', ".favor-this", function() {
        var that = $(this);
        var item_option = $(this).parents('.item-option');

        $.post(
            "/item/favor/save",
            {
                _token: $('meta[name="_token"]').attr('content'),
                course_id: item_option.attr('data-id'),
                content_id: 0,
                type: 1
            },
            function(data){
                if(!data.success) layer.msg(data.msg);
                else
                {
                    layer.msg("点赞成功");
                    item_option.html(data.data.html);
                }
            },
            'json'
        );
    });
    // 取消点赞
    $(".item-option").off("click",".favor-this-cancel").on('click', ".favor-this-cancel", function() {
        var that = $(this);
        var item_option = $(this).parents('.item-option');

        layer.msg('取消"点赞"？', {
            time: 0
            ,btn: ['确定', '取消']
            ,yes: function(index){
                $.post(
                    "/item/favor/cancel",
                    {
                        _token: $('meta[name="_token"]').attr('content'),
                        course_id: item_option.attr('data-id'),
                        content_id: 0,
                        type: 1
                    },
                    function(data){
                        if(!data.success) layer.msg(data.msg);
                        else
                        {
                            item_option.html(data.data.html);
                            layer.closeAll();
                            // var index = parent.layer.getFrameIndex(window.name);
                            // parent.layer.close(index);
                        }
                    },
                    'json'
                );
            }
        });
    });


});

