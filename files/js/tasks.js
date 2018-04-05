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

    }

    function getData()
    {
        return [

        ]
    }
 }
)()