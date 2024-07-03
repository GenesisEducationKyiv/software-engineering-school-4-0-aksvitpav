<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Daily Exchange Rates</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
<h1>Daily Exchange Rates</h1>
<p>Here are the current buy and sell rates for USD:</p>
<table>
    <thead>
    <tr>
        <th>Currency</th>
        <th>Buy Rate</th>
        <th>Sell Rate</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>USD</td>
        <td>{{ $USDBuyRate }}</td>
        <td>{{ $USDSaleRate }}</td>
    </tr>
    </tbody>
</table>
<p>Thank you for using our service!</p>
</body>
</html>
