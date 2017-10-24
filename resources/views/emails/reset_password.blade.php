<table>
    <tr>
        <td>
            Hi {{ $user_name }}!
        </td>
    </tr>
    <tr>
        <td>

            We received a request to reset your {{ $app_name }} password.
            <br/>
            Please click on the link below to complete the process:

        </td>
    </tr>
    <tr>
        <td>
            {{ $password_reset_link }}
        </td>
    </tr>
    <tr>
        <td>
            Best regards,
            <br/>
            <br/>
            The {{ $app_name }} Team
        </td>
    </tr>

</table>