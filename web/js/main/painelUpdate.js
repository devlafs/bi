$(document).ready(function() 
{
    $(".config-update-consulta.open-config").click(function(e) 
    {
        e.preventDefault();
        
        if($(".sidebar--painel").hasClass("block__slide"))
        {
            $(".sidebar--painel .tab-content #filtro").html('');
            var _id = $(this).data('id');

            jQuery.ajax({
                url: '/consulta/open-filter-update?id=' + _id,
                success: function (_data) 
                {
                    $(".sidebar--painel .tab-content #filtro").html(_data);

                    $(".sidebar--painel").toggleClass("block__slide");
                },
                beforeSend: function ()
                {
                    $('.div-loading').addClass("loading");
                },
                complete: function () 
                {
                    setTimeout(function() { $('.div-loading').removeClass("loading");}, 300);
                }
            });
        }
        else
        {
            $(".sidebar--painel").toggleClass("block__slide");
        }
    });
    
    $(".close-painel").click(function(e) 
    {
        e.preventDefault();
        $(".sidebar--painel").toggleClass("block__slide");
    });
});