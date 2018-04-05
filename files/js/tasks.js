(function()
 {
    $(function() {
        alert('DOM Loaded')
        
        UpdateData(getData())
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
        return [
            {
                task_id     : 1,
                name        : "Name 1",
                description : "Description 1",
                start       : "01/02/2018",
                finish      : "01/03/2018",
                status      : "Pendente",
                state       : "ativo"
            },
            {
                task_id     : 2,
                name        : "Name 2",
                description : "Description 2",
                start       : "01/03/2018",
                finish      : "01/04/2018",
                status      : "Pendente",
                state       : "ativo"
            }
        ]
    }
 }
)()