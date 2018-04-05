(function()
 {
    $(function() {      
        getData()
        $(document).keydown(function (event) { 
            if (event.ctrlKey)
            {
                if (event.which == 73) // CTRL + I
                {
                    $('.table tbody').append($("#task-row").html())
                }
            } 
                            })
      })

    function UpdateData(data)
    {
        data.forEach(function (item, index) {
            var template

            template = $('#task-row').html()
            template = template.replace('{sequence}',    index + 1)
            template = template.replace('{name}',        item.name)
            template = template.replace('{description}', item.description)
            template = template.replace('{start}',       item.start)
            template = template.replace('{finish}',      item.finish)
            template = template.replace('{status}',      item.status)
            template = template.replace('{state}',       item.state)

            $('.table tbody').append(template)
        })
    }

    function getData()
    {
        $.get('static.php', UpdateData)
    }
 }
)()