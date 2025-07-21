<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Our Response</title>
  <style>
    /* Include your styles here */
  </style>
</head>
<body>
  <table>
    <tr>
      <td>
        <h2>E-TICKET SYSTEM - URGENT NOTIFICATION</h2>
        <p>{{ $toName }}, How are you doing?</p>
        <p>You have an urgent message:</p>
        <p>{!! $msg !!}</p>
        <p><a href="{{ request()->getHost() }}">Visit Us!</a></p>
        <br>
        <p>If you would like to reach out, talk to us via the feedback section in your account. Thank You!</p>
        <footer>
          <p>{{ $title }}</p>
        </footer>
      </td>
    </tr>
  </table>
</body>
</html>
