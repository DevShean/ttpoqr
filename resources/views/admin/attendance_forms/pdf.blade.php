<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Attendance Sheet</title>

    <style>
        font-family: "DejaVu Sans", Arial, Helvetica, sans-serif;
        @page {
            size: landscape;
            margin: 12mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #000;
        }

        .container {
            width: 100%;
        }

        /* HEADER */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }

        .logo {
            display: table-cell;
            width: 80px;
            vertical-align: middle;
        }

        .logo img {
            width: 70px;
        }

        .header-text {
            display: table-cell;
            text-align: center;
            vertical-align: middle;
        }

        .header-text p {
            margin: 2px 0;
            font-size: 11px;
        }

        .header-text .bold {
            font-weight: bold;
        }

        hr {
            border: none;
            border-top: 2px solid #000;
            margin: 8px 0;
        }

        /* FORM INFO */
        .form-info {
            margin-bottom: 10px;
        }

        .form-info span {
            margin-right: 40px;
        }

        .label {
            font-weight: bold;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        th, td {
            border: 1px solid #000;
            padding: 4px;
            font-size: 10px;
        }

        th {
            font-weight: bold;
            text-align: center;
        }

        td {
            height: 22px;
        }

        .center {
            text-align: center;
        }

    </style>
</head>

<body>
<div class="container">

    <!-- HEADER -->
    <div class="header">
        <div class="logo">
            <img src="{{ public_path('assets/img/paplogo.png') }}">
        </div>

        <div class="header-text">
            <p class="bold">Republic of the Philippines</p>
            <p class="bold">Department of Justice</p>
            <p class="bold">PAROLE AND PROBATION ADMINISTRATION</p>
            <p class="bold">REGIONAL OFFICE NO. VIII</p>
            <p class="bold">Tacloban City Parole and Probation Office</p>
        </div>

        <div class="logo" style="text-align:right;">
            <img src="{{ public_path('assets/img/bplogo.png') }}">
        </div>
    </div>

    <hr>

    <!-- FORM INFO -->
    <div class="form-info">
        <span><span class="label">ACTIVITIES CONDUCTED:</span> {{ $form->activities_conducted }}</span><br><br>
        <span><span class="label">DATE:</span> {{ $form->date->format('m/d/Y') }}</span>
        <span><span class="label">VENUE:</span> {{ $form->venue }}</span>
    </div>

    <!-- TABLE -->
    <table>
        <thead>
            <tr>
                <th width="5%">PS/PR</th>
                <th width="5%">No.</th>
                <th width="20%">Name</th>
                <th width="6%">Male</th>
                <th width="6%">Female</th>
                <th width="20%">Address</th>
                <th width="15%">Signature</th>
                <th width="10%">Family Support</th>
                <th width="13%">Contact Number</th>
            </tr>
        </thead>

        <tbody>
        @foreach($form->records as $index => $record)
            <tr>
                <td class="center">{{ $record->type ?? '' }}</td>
                <td class="center">{{ $index + 1 }}</td>
                <td>{{ $record->name }}</td>
                <td class="center">{{ (strtolower($record->gender) == 'male' || $record->gender == 'M') ? '✓' : '' }}</td>
                <td class="center">{{ (strtolower($record->gender) == 'female' || $record->gender == 'F') ? '✓' : '' }}</td>
                <td>{{ $record->address }}</td>
                <td></td>
                <td class="center">{{ $record->family_support ? 'Yes' : 'No' }}</td>
                <td class="center">{{ $record->contact_number }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
</body>
</html>
