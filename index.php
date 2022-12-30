<?php

declare(strict_types=1);

use App\Builders\FlightPathBuilder;
use App\Entities\Airline;
use App\Entities\Airport;

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
    <title>Flight Search</title>
    <style>
        body {
            background-color: #000;
            color: #fff;
        }

        .form-layout {
            display: flex;
            flex-direction: column;
            max-width: 800px;
            gap: 15px;
        }

        .divider {
            height: 1px;
            background-color: #fff;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .api-output {
            animation: flash 2s;
        }

        @keyframes flash {
            0% {
                background-color: rgba(0, 100, 0, 0.5);
            }

            100% {
                background-color: #000;
            }
        }
    </style>
</head>
<body>
    <div>
        <form action="/api.php" method="get">
            <div class="form-layout">
                <label>
                    Departure Airport:
                    <select name="departure_airport" required>
                        <?php foreach ($airports->toPrimitiveArray() as $airport): ?>
                            <option value="<?php echo $airport->code; ?>"><?php echo "{$airport->name} ({$airport->code})"; ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>
                    Arrival Airport:
                    <select name="arrival_airport" required>
                        <?php foreach ($airports->toPrimitiveArray() as $airport): ?>
                            <option value="<?php echo $airport->code; ?>"><?php echo "{$airport->name} ({$airport->code})"; ?></option>
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
            </div>
            <div class="divider"></div>
            <div class="form-layout">
                <label>
                    Optional fields
                </label>
                <label>
                    Return Date:
                    <input type="date" name="return_date">
                </label>
                <label>
                    Preferred airline:
                    <select name="preferred_airline">
                        <option value="">Any</option>
                        <?php foreach ($airlines->toPrimitiveArray() as $airline): ?>
                            <option value="<?php echo $airline->code; ?>"><?php echo "{$airline->name} ({$airline->code})"; ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
            </div>
            <div class="divider"></div>
            <div class="form-layout">
                <input type="submit" value="Submit" />
                <div>
                    <label for="api-call-preview">API Request:</label>
                    <pre id="api-call-preview">

                    </pre>
                </div>
                <div>
                    <label for="api-call-output">API Response:</label>
                    <pre id="api-call-output">

                    </pre>
                </div>
            </div>
        </form>
    </div>
</body>

<script lang="javascript">

function updateAPIPreview() {
    const form = document.querySelector('form');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    const json = JSON.stringify(data, null, 4);
    document.querySelector('#api-call-preview').innerHTML = json;
}

let cooldown = false;
function flashAPIOutput() {
    if (cooldown) {
        return;
    }

    const output = document.querySelector('#api-call-output');
    output.classList.add('api-output');
    cooldown = true;

    setTimeout(() => {
        output.classList.remove('api-output');
        cooldown = false;
    }, 2000);
}

updateAPIPreview();

document.querySelectorAll('input, select').forEach((element) => {
    element.addEventListener('change', updateAPIPreview);
});

document.querySelector('form').addEventListener('submit', (event) => {
    event.preventDefault();
    const form = document.querySelector('form');
    const formData = new FormData(form);
    const url = new URL('/api.php', window.location.origin);
    url.search = new URLSearchParams(formData).toString();
    fetch(url.toString())
        .then((response) => response.text())
        .then((text) => {
            try {
                const json = JSON.parse(text);
                text = JSON.stringify(json, null, 4);
            } catch (e) {
                // do nothing
            }
            document.querySelector('#api-call-output').innerHTML = text;
            flashAPIOutput();
        });
});

</script>

</html>