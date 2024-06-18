<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .selector-container {
            display: flex;
            gap: 10px;
            margin: 10px;
        }

        .dropdown select {
            padding: 10px;
            font-size: 16px;
            border-radius: 20px;
            border: 1px solid #cb0c9f;
            appearance: none;
            background-color: #cb0c9f;
            cursor: pointer;
            width: 150px;
            color: #ffffff;
        }

        .dropdown {
            position: relative;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 20px;
            background-color: #cb0c9f;
            color: #ffffff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #a00d84;
        }

        button i {
            margin-right: 0;
        }
    </style>
</head>

<body>
    <div class="selector-container">
        <div class="dropdown">
            <form action="{{ url()->current() }}" method="GET" onsubmit="combineQuery()">
                <select id="year-select">
                </select>
                <select id="month-select">
                </select>
                <input type="hidden" name="query" id="query-input">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const yearSelect = document.getElementById("year-select");
            const monthSelect = document.getElementById("month-select");
            const queryInput = document.getElementById("query-input");

            const currentYear = new Date().getFullYear();
            const params = new URLSearchParams(window.location.search);
            const query = params.get('query');
            let selectedYear = null;
            let selectedMonth = null;

            if (query) {
                const [year, month] = query.split('-');
                selectedYear = parseInt(year);
                selectedMonth = parseInt(month);
            }

            // Populate year select
            for (let year = currentYear; year >= 2020; year--) {
                const option = document.createElement("option");
                option.value = year;
                option.textContent = year;
                if (selectedYear && selectedYear === year) {
                    option.selected = true;
                }
                yearSelect.appendChild(option);
            }

            // Populate month select
            const months = [{
                    value: 1,
                    name: 'January'
                },
                {
                    value: 2,
                    name: 'February'
                },
                {
                    value: 3,
                    name: 'March'
                },
                {
                    value: 4,
                    name: 'April'
                },
                {
                    value: 5,
                    name: 'May'
                },
                {
                    value: 6,
                    name: 'June'
                },
                {
                    value: 7,
                    name: 'July'
                },
                {
                    value: 8,
                    name: 'August'
                },
                {
                    value: 9,
                    name: 'September'
                },
                {
                    value: 10,
                    name: 'October'
                },
                {
                    value: 11,
                    name: 'November'
                },
                {
                    value: 12,
                    name: 'December'
                }
            ];

            months.forEach(month => {
                const option = document.createElement("option");
                option.value = month.value;
                option.textContent = month.name;
                if (selectedMonth && selectedMonth === month.value) {
                    option.selected = true;
                }
                monthSelect.appendChild(option);
            });
        });

        function combineQuery() {
            const yearSelect = document.getElementById("year-select");
            const monthSelect = document.getElementById("month-select");
            const queryInput = document.getElementById("query-input");

            const selectedYear = yearSelect.value;
            const selectedMonth = monthSelect.value.padStart(2, '0'); // Ensure month is two digits

            queryInput.value = `${selectedYear}-${selectedMonth}`;
        }
    </script>
</body>

</html>
