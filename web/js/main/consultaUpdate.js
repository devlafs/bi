$(document).on('click', '.attr-list#argumento .dropdown-menu', function (e)
{
    e.stopPropagation();
});

$(document).ready(function()
{
    $('.choose-type').on("click", function(e)
    {
        var _val = $(this).data('val');
        var _index = $(this).data('index');
        var _elli = $('li#el-' + _index);
        var _elitem = $('.data-type.el-item-' + _index);
        _elli.attr('data-type', _val);
        _elitem.attr('data-type', _val);

        _elitem.find('i').removeClass('bp-chart--pie').removeClass('bp-chart--grid').removeClass('bp-chart--colum').removeClass('bp-chart--bar').removeClass('bp-chart--area').removeClass('bp-chart--line');
        _elitem.parent().find('.dropdown-menu .dropdown-item span').remove();

        switch(_val) 
        {
            case 'pie':
                _elitem.find('i').addClass('bp-chart--pie');
                break;
            case 'table':
                _elitem.find('i').addClass('bp-chart--grid');
                break;
            case 'bar':
                _elitem.find('i').addClass('bp-chart--bar');
                break;
            case 'column':
                _elitem.find('i').addClass('bp-chart--colum');
                break;
            case 'area':
                _elitem.find('i').addClass('bp-chart--area');
                break;
            case 'line':
                _elitem.find('i').addClass('bp-chart--line');
                break;
            default:
        }

        $(this).append('<span class="badge badge-default badge-pill ml-auto">ATIVO</span>');
        e.stopPropagation();
        e.preventDefault();
    });

    $('.choose-sort').on("click", function(e)
    {
        var _val = $(this).data('val');
        var _index = $(this).data('index');
        var _elli = $('li#el-' + _index);
        var _elitem = $('.data-sort.el-item-' + _index);

        _elli.attr('data-sort', _val);
        _elitem.attr('data-sort', _val);

        _elitem.find('svg').removeClass('fa-sort-alpha-down').removeClass('fa-sort-alpha-up');
        _elitem.parent().find('.dropdown-menu .dropdown-item span').remove();

        if(_val == 0)
        {
            _elitem.find('svg').addClass('fa-sort-alpha-down');
        }
        else
        {
            _elitem.find('svg').addClass('fa-sort-alpha-up');
        }

        $(this).append('<span class="badge badge-default badge-pill ml-auto">ATIVO</span>');
        e.stopPropagation();
        e.preventDefault();
    });
});