<?php

declare(strict_types=1);

use App\Builders\FlightPathBuilder;
use App\Entities\Airline;
use App\Entities\Airport;
use App\Entities\Flight;

require_once('vendor/autoload.php');

function getArrayFromJsonFile(string $filename, string $key)
{
    $json = file_get_contents(__DIR__ . "/dataset/$filename");
    return json_decode($json, true)[$key];
}

$airlines = Airline::fromJsonArray(getArrayFromJsonFile('airlines.json', 'airlines'));
$airports = Airport::fromJsonArray(getArrayFromJsonFile('airports.json', 'airports'));

?>
<html>

<head>
    <title>My First Page</title>
    <style>
        body {
            background-color: #000;
            color: #fff;
        }

        .form-layout {
            display: flex;
            flex-direction: column;
            max-width: 800px;
            gap: 10px;
        }
    </style>
</head>
<body>
    <div>
        <form action="/api.php" method="get">
            <div class="form-layout">
                <label>
                    Departure Date:
                    <select name="departure_airport" required>
                        <?php foreach ($airports->toPrimitiveArray() as $airport): ?>
                            <option value="<?php echo $airport->code; ?>"><?php echo $airport->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>
                    Arrival Date:
                    <select name="arrival_airport" required>
                        <?php foreach ($airports->toPrimitiveArray() as $airport): ?>
                            <option value="<?php echo $airport->code; ?>"><?php echo $airport->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>
                    Departure Date:
                    <input type="date" name="departure_date" value="<?php echo date('Y-m-d'); ?>" required>
                </label>
                <div>
                    <label>
                        Trip Type:
                        <select name="trip_type" required>
                            <option value="one-way">One Way</option>
                            <option value="round-trip">Round Trip</option>
                        </select>
                    </label>
                </div>
                <label>
                    Return Date:
                    <input type="date" name="return_date">
                </label>
                <input type="submit" value="Submit" />
                <div>
                    <p id="api-call-preview">

                    </p>
                </div>
                <div>
                    <p id="api-call-output">

                    </p>
                </div>
            </div>
        </form>
    </div>
</body>

<script lang="javascript">
    function updateAPIPreview() {
        const form = document.querySelector('form');
        const formData = new FormData(form);
        const url = new URL('/api.php', window.location.origin);
        url.search = new URLSearchParams(formData).toString();
        document.querySelector('#api-call-preview').innerText = url.toString();
    }

    document.querySelectorAll('input, select').forEach((element) => {
        element.addEventListener('change', updateAPIPreview);
    });

    // perform update on page ready
    updateAPIPreview();

    // on submit, make an API call to the API and print response without leaving Page
    // print response even if it's 400
    document.querySelector('form').addEventListener('submit', (event) => {
        event.preventDefault();
        const form = document.querySelector('form');
        const formData = new FormData(form);
        const url = new URL('/api.php', window.location.origin);
        url.search = new URLSearchParams(formData).toString();
        fetch(url.toString())
            .then((response) => response.text())
            .then((text) => {
                document.querySelector('#api-call-output').innerHTML = text;
            });
    });
</script>

</html>