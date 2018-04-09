(function()
 {
    $(function() {      
        getData()
        $('.btn-add' ).each(function(index) {
            $(this).click(function() { TaskDialog('insert') })
        })

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


    function StatusList(data)
    {
        var template
        var tag

        template = $('#status-item').html()
        data.forEach(function (item) {
            tag = template
            tag = tag.replace('{status_id}',    item.status_id)
            tag = tag.replace('{status_name}',  item.name)
            
            $('#task-status').append(tag)
        })
    }


    function TaskListAppendRecord(item, index)
    {
        var tag

        tag = $('#task-row').html()
        tag = tag.replace('{sequence}',    index + 1)
        tag = tag.replace('{name}',        item.name)
        tag = tag.replace('{description}', item.description)
        tag = tag.replace('{start}',       item.start)
        tag = tag.replace('{finish}',      item.finish)
        tag = tag.replace('{status}',      item.status)
        tag = tag.replace('{state}',       item.state)

        tag = $(tag).appendTo($('.table tbody'))
        if (item.status == "Concluído")
        {
            tag.css('background-color', 'cyan')
        }

        tag.find('.btn-edit').click(function(e) { 
            TaskDialog('update', tag) 
        })
        tag.data = item;
    }

    function TaskDialog(operation, target)
    {
        if (operation == 'update')
        {
            $('#task-dialog').find('.modal-title').html('Editar Tarefa')
            $('#task-name').val(target.data.name)
            $('#task-description').val(target.data.description)
            $('#task-start').val(target.data.start)
            $('#task-finish').val(target.data.finish)
            $('#task-status').val(target.data.status)
            $('#task-active').val(target.data.active)
        }

        if (operation == 'insert')
        {
            $('#task-dialog').find('.modal-title').html('Adicionar Tarefa')
            $('#task-name').val('')
            $('#task-description').val('')
            $('#task-start').val('')
            $('#task-finish').val('')
            $('#task-status').val('')
            $('#task-active').val('')
        }

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
            type:"PUT",
            data: JSON.stringify(data),
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

        $.get('tasks.php?status')
           .done(StatusList)
           .fail(function() { alert("ajax error") })   
    }
 }
)()