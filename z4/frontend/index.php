<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" href="/z4/frontend/assets/wapp.ico" type="image/x-icon">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
    integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
    integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
    crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct"
    crossorigin="anonymous"></script>
  <title>Weather app</title>
  <style>
    #fixedTable {
      position: fixed;
      top: 10px;
      right: 10px;
      width: auto;
      z-index: 999;
      border-radius: 5px;
    }

    #searchStatsTable {
      max-height: 200px;
      background-color: white;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      max-height: 200px;
      overflow-y: scroll;
    }

    #visitStatsTable {
      position: fixed;
      bottom: 0;
      left: 0;
      max-width: 150px;
      z-index: 1030;
      background-color: #fff;
      border-top-right-radius: 0.25rem;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
      overflow-y: auto;
    }
  </style>
</head>
<style>
  button,
  th,
  a {
    text-transform: uppercase;
  }
</style>
<style>
  .cookie-consent-container {
    display: none;
    position: fixed;
    top: 0;
    width: 100%;
    background-color: #f1f1f1;
    padding: 10px;
    text-align: center;
    box-shadow: 0px -2px 5px rgba(0, 0, 0, .2);
    z-index: 1000;
  }
</style>
</head>

<body>

  <div class="cookie-consent-container" id="cookieConsentContainer">
    <p>This website uses cookies to improve the user experience.</p>
    <button class="btn btn-primary btn-sm" id="acceptCookieConsent">Accept</button>
  </div>

  <script>
    $(document).ready(function () {
      if (localStorage.getItem('cookieConsent') !== 'true') {
        $('#cookieConsentContainer').show();
      }

      $('#acceptCookieConsent').click(function () {
        localStorage.setItem('cookieConsent', 'true');
        $('#cookieConsentContainer').hide();
      });
    });
  </script>
  <script>
    $(document).ready(function () {
      $('#searchForm').submit(function (event) {
        event.preventDefault();
        var cityName = $('#cityName').val();
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();

        $.ajax({
          type: "POST",
          url: "https://node86.webte.fei.stuba.sk/weather-api/get-info",
          contentType: "application/json",
          data: JSON.stringify({
            city_name: cityName,
            start_date: startDate,
            end_date: endDate
          }),
          success: function (data) {
            $('#result').empty();
            $('#generalInfo').empty();
            var sumTemp = 0;
            var count = data.daily.temperature_2m_max.length;
            data.daily.temperature_2m_max.forEach(temp => {
              sumTemp += temp;
            });
            var avgTemp = (sumTemp / count).toFixed(2);

            var infoHtml = '<div class="media">' +
              '<img src="' + data.flag + '" class="mr-3" alt="Flag" style="width: 100px;">' +
              '<div class="media-body">' +
              '<h5 class="mt-0">' + data.city_name + ', ' + data.country + '</h5>' +
              '<p>Capital: ' + data.capital + '</p>' +
              '<p>Currency: ' + data.currencies[0].name + ' (' + data.currencies[0].symbol + ')</p>';
            if (data.currencies[0].code !== 'EUR') {
              infoHtml += '<p>Exchange Rate to EUR: ' + data.exchange_rate + ' (1 EUR =' + (1 / data.exchange_rate).toFixed(2) + ' ' + data.currencies[0].code + ' )' + '</p>';
            }
            infoHtml += '<p>Average Temperature: ' + avgTemp + '°C</p>' +
              '</div>' +
              '</div>';
            $('#generalInfo').html(infoHtml);
            if (data.daily) {
              data.daily.time.forEach((time, index) => {
                var cardHtml = '<div class="col-md-4 mb-4">' +
                  '<div class="card">' +
                  '<h5 class="card-header">' + time + '</h5>' +
                  '<div class="card-body alert alert-success mb-0">' +
                  '<p class="card-text">Max Temp: ' + data.daily.temperature_2m_max[index] + '°C</p>' +
                  '<p class="card-text">Min Temp: ' + data.daily.temperature_2m_min[index] + '°C</p>' +
                  '<p class="card-text">Precipitation: ' + data.daily.precipitation_sum[index] + ' mm</p>' +
                  '<p class="card-text">Max Wind Speed: ' + data.daily.windspeed_10m_max[index] + ' km/h</p>' +
                  '</div>' +
                  '</div>' +
                  '</div>';
                $('#result').append(cardHtml);
              });
            }
          },
          error: function (xhr, status, error) {
            console.error("Error: " + error);
            $('#generalInfo').html('<div class="alert alert-danger" role="alert">Error loading data. Check console for details.</div>');
          }
        });
      });
    });
    function updateTable() {
      $.ajax({
        url: 'z4/frontend/php/getSearchStatistics.php',
        type: 'GET',
        success: function (data) {
          var tbody = $('#fixedTable tbody');
          tbody.empty();
          JSON.parse(data).forEach(function (row) {
            tbody.append(
              '<tr>' +
              '<td>' + row.destination_name + '</td>' +
              '<td>' + row.country + '</td>' +
              '<td>' + row.search_count + '</td>' +
              '</tr>'
            );
          });
        },
        error: function (xhr, status, error) {
          console.error('Error: ' + error);
        }
      });
    }
    function updateVisitStats() {
      $.ajax({
        url: 'z4/frontend/php/getVisitsStatistics.php',
        type: 'GET',
        success: function (data) {
          var html = '<table class="table">';

          JSON.parse(data).forEach(function (stat) {
            html += '<tr><td>' + stat.time_frame + '</td><td>' + stat.visit_count + '</td></tr>';
          });

          html += '</tbody></table>';
          $('#visitStatsTable').html(html);
        },
        error: function (xhr, status, error) {
          console.error('Error: ' + error);
        }
      });
    }
    setInterval(updateTable, 3000);
    setInterval(updateVisitStats, 3000);

    $(document).ready(function () {
      updateTable();
      updateVisitStats();
      $.ajax({
        url: 'https://node86.webte.fei.stuba.sk/weather-api/start-session',
        type: 'GET',
        success: function (response) {
          console.log('Session status:', response.session_status);
        },
        error: function (xhr, status, error) {
          console.error('Error starting session:', error);
        }
      });
    });
  </script>



  <body>
    <?php
    require 'menu/menu.php';
    ?>
    <div class="container mt-0">
      <h2>Weather Details</h2>
      <form id="searchForm">
        <div class="form-row">
          <div class="form-group col-sm-12 col-md">
            <label for="cityName">City Name</label>
            <input type="text" class="form-control" id="cityName" required>
          </div>
          <div class="form-group col-sm-12 col-md">
            <label for="startDate">Start Date</label>
            <input type="date" class="form-control" id="startDate" required>
          </div>
          <div class="form-group col-sm-12 col-md">
            <label for="endDate">End Date</label>
            <input type="date" class="form-control" id="endDate" required>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col">
            <button type="submit" class="btn btn-primary mt-2">Search</button>
          </div>
        </div>
      </form>
    </div>


    <div class="container mt-1">
      <div id="generalInfo" class="mb-2 alert alert-success "></div>
      <div id="result" class="row">
      </div>
    </div>
    <div id="fixedTable">
      <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#searchStatsTable"
        aria-expanded="false" aria-controls="searchStatsTable">
        Search Stats
      </button>
      <div class="collapse" id="searchStatsTable">
        <table class="table">
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
    <div id="visitStatsTable" class="fixed-bottom left-0 p-0">
    </div>
  </body>

</html>