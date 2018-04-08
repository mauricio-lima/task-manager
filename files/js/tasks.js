(function()
 {
    $(function() {      
        getData()
        $('#add').click(TasksAdd)

        $('#task-dialog > div .modal-footer .btn-success').click(TaskDialogConfirmation)

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
            TaskListAppendRecord(item, index)
        })
    }

    function TaskListAppendRecord(item, index)
    {
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
    }

    function TasksAdd()
    {
        $('#task-dialog').modal('show')

    }

    function TaskDialogConfirmation()
    {
        var data

        data = {
            name : $('#task-name').val(),
            description : $('#task-description').val(),
            start : $('#task-start').val(),
            finish : $('#task-finish').val(),
        }

        $.ajax({
            url:'tasks.php',
            type:"POST",
            data: JSON.stringify({ action : "insert", data : data }),
            contentType:"application/json; charset=utf-8",
            dataType:"json"
          })
          .done(function (data) {
              if (!data.success)         return
              if (data.success !== true) return

              TaskListAppendRecord(data.record, 20)
          })
          .fail(function() { alert("ajax post error") })

    }

    function getData()
    {
        $.get('tasks.php')
           .done(UpdateData)
           .fail(function() { alert("ajax error") })
    }
 }
)()