<fieldset style="margin:auto;padding:10px;width:50%">
    <legend>忘記密碼</legend>
    <table>
        <tr>
            <td width="90%"><input type="text" name="email" id="email" style="width:90%"></td>
        </tr>

        <tr>
            <td id="result"></td>
        </tr>
        <tr>
            <td><input type="button" value="尋找" onclick="findPw()">
            </td>
        </tr>
    </table>


</fieldset>
<script>
    function findPw() {
        let acc = document.querySelector("#email").value; //後面的.value和val() 不可混用
        // let acc = $("#acc").val();

        let email = $("#email").val();
        if (email == "") {
            alert("欄位不可為空白");
        } else {
            $.get("api/find_pw.php", {
                email
            }, function(res) {
$("#result").html(res)
            })
        }
    }
</script>