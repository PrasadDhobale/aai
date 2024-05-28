<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Visitor Pass Rejected</title>
</head>
<body>
    <div class="container">
        <br>        
        <div style="font-family:sans-serif;padding:10px;">
            <h1>Visitor Pass Rejected</h1>
            <p style="font-family: monospace">Pass has been rejected</p>
            <br>
            <p style="font-size: 20px; text-align: left;">Convey this message to your employee. <br><br>
            Below are the details should be updated by your employee.<br>
            </p>
            <div class="rejection-details">
                <p>Rejection Reason : <?php echo $rejectReason; ?></p>
                <p>Rejection By : <?php echo $rejectedByRole; ?></p>
                <p>Rejected At : <?php echo $rejection_time; ?></p>
            </div>
        </div>
    </div>
</body>
</html>