$(document).ready(function () {
    $("button#dashboard-user-logoff").off().on('click', function () {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '/ajax/logoff/',
            data: {
            },
            success: function (response)
            {
                if (response.code == 200)
                {
                    if (response.ret == true)
                    {
                        location.href = "/";
                        location.reload();
                    }
                    if (response.ret == false)
                    {
                        alert(response.body);
                    }
                } else {
                    alert(response.body);
                }
            }
        });
    });
    
});

class ajax_response
{
    constructor(presponse) {
        this.code = presponse.code;
        if (presponse.hasOwnProperty("ret")) {
            this.returned = presponse.ret;
        } else { this.returned = false; }
        this.body = presponse.body;
    }

    get_body()
    {
        return this.body;
    }

    get_code() {
        return this.code;
    }

    has_returned()
    {
        return this.returned;
    }

    is_successful() {
        if (this.code == '200') return true;
        return false;
    }

    is_denied() {
        if (this.code == '403') return true;
        return false;
    }

    is_invalid() {
        if (this.code == '404') return true;
        return false;
    }

    is_bugged() {
        if (this.code == '500') return true;
        return false;
    }

}