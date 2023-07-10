<?php
require_once __DIR__ . '/config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="" />
  <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors" />
  <meta name="generator" content="Hugo 0.84.0" />
  <title>BTC Dashboard</title>

  <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/dashboard/" />

  <!-- Bootstrap core CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />

  <style>
    .bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      user-select: none;
    }

    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 3.5rem;
      }
    }

    .switcher {
      display: flex;
      align-items: center;
      height: 30px;
      margin-top: 8px;
      color: #2196F3;
    }

    .switcher-item {
      cursor: pointer;
      text-decoration: none;
      display: inline-block;
      padding: 6px 8px;
      font-size: 14px;
      color: #262b3e;
      background-color: transparent;
      margin-right: 8px;
      border: none;
      border-radius: 4px;
      outline: none;
    }

    .switcher-item:hover {
      background-color: #f2f3f5;
    }

    .switcher-active-item {
      text-decoration: none;
      cursor: default;
      color: #262b3e;
    }

    .switcher-active-item,
    .switcher-active-item:hover {
      background-color: #e1eff9;
    }
  </style>

  <!-- Custom styles for this template -->
  <link href="dashboard.css" rel="stylesheet" />

  <script src="https://unpkg.com/lightweight-charts/dist/lightweight-charts.standalone.production.js"></script>
  <script src="js/table2csv.min.js"></script>
</head>

<?php
class MyDateTime extends \DateTime implements \JsonSerializable
{
  public function jsonSerialize()
  {
    return $this->format("Y-m-d H:i:s");
  }
}
?>

<body>
  <div class="container-fluid">
    <div class="row">
      <main class="col-md-9 ms-sm-auto col-lg-12 px-md-4">
        <div
          class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h1 class="h2">Dashboard</h1>

          <input type="text" class="form-control w-25 fe" id="dateRange" />

          <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
              <button type="button" data-time="hour" class="btn btn-sm btn-outline-secondary btn-time">
                1H
              </button>
              <button type="button" data-time="day" class="btn btn-sm btn-outline-secondary btn-time">
                1D
              </button>
              <button type="button" data-time="month" class="btn btn-sm btn-outline-secondary btn-time">
                1M
              </button>
              <button type="button" data-time="year" class="btn btn-sm btn-outline-secondary btn-time">
                1Y
              </button>
            </div>
          </div>
        </div>

        <div id="newChart" style="m-3"></div>


        <div class="table-responsive">
          <table class="table table-striped table-sm">
            <thead>
              <tr>
                <th scope="col">Date Time</th>
                <th scope="col">
                  Price
                  <button class="btn btn-sm btn-primary btn-csv float-end">CSV</button>
                </th>
              </tr>
            </thead>
            <tbody id="tblPrice">

            </tbody>
          </table>
        </div>
      </main>
    </div>
  </div>

  <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js"
    integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"
    integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha"
    crossorigin="anonymous"></script>

  <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>

  <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

  <script>

    function createSimpleSwitcher(
      items,
      activeItem,
      activeItemChangedCallback
    ) {
      var switcherElement = document.createElement("div");
      switcherElement.classList.add("switcher");

      var intervalElements = items.map(function (item) {
        var itemEl = document.createElement("button");
        itemEl.innerText = item;
        itemEl.classList.add("switcher-item");
        itemEl.classList.toggle("switcher-active-item", item === activeItem);
        itemEl.addEventListener("click", function () {
          onItemClicked(item);
        });
        switcherElement.appendChild(itemEl);
        return itemEl;
      });

      function onItemClicked(item) {
        if (item === activeItem) {
          return;
        }

        intervalElements.forEach(function (element, index) {
          element.classList.toggle(
            "switcher-active-item",
            items[index] === item
          );
        });

        activeItem = item;

        activeItemChangedCallback(item);
      }

      return switcherElement;
    }

    var intervals = ["1H", "1D", "1W", "1M", "1Y"];

    var hourData = <?= json_encode($hourly_data) ?>;

    var dayData = <?= json_encode($daily_data) ?>;

    var weekData = []

    var monthData = <?= json_encode($monthly_data) ?>;

    var yearData = <?= json_encode($yearly_data) ?>;

    var seriesesData = new Map([
      ["1H", hourData],
      ["1D", dayData],
      ["1W", weekData],
      ["1M", monthData],
      ["1Y", yearData],
    ]);

    var switcherElement = createSimpleSwitcher(
      intervals,
      intervals[0],
      syncToInterval
    );

    var chartElement = document.querySelector("#newChart");

    console.log(chartElement.offsetWidth);
    console.log(chartElement.offsetHeight);

    var chart = LightweightCharts.createChart(chartElement, {
      width: chartElement.offsetWidth,
      height: 600,
      layout: {
        backgroundColor: "#ffffff",
        textColor: "#000000",
      },
      grid: {
        vertLines: {
          visible: false,
        },
        horzLines: {
          visible: false,
          color: "rgba(42, 46, 57, 0.5)",
        },
      },
      rightPriceScale: {
        borderVisible: false,
      },
      timeScale: {
        borderVisible: true,
        timeVisible: true,
      },
      crosshair: {
        horzLine: {
          visible: false,
        },
      },
      localization: {
        dateFormat: 'yyyy-MM-dd',
      },
    });

    //document.body.appendChild(chartElement);
    document.body.appendChild(switcherElement);

    var areaSeries = null;

    function syncToInterval(interval) {
      if (areaSeries) {
        chart.removeSeries(areaSeries);
        areaSeries = null;
      }
      areaSeries = chart.addAreaSeries({
        topColor: "rgba(76, 175, 80, 0.56)",
        bottomColor: "rgba(76, 175, 80, 0.04)",
        lineColor: "rgba(76, 175, 80, 1)",
        lineWidth: 2,
        priceFormat: {
          type: 'price',
          precision: 6,
          minMove: 0.000001,
        },
      });
      console.log(seriesesData.get(interval))
      areaSeries.setData(seriesesData.get(interval));

      chart.timeScale().fitContent();
    }

    syncToInterval(intervals[4]);

    // Make Chart Responsive with screen resize
    new ResizeObserver((entries) => {
      if (entries.length === 0 || entries[0].target !== chartElement) {
        return;
      }
      const newRect = entries[0].contentRect;
      chart.applyOptions({ height: newRect.height, width: newRect.width });
    }).observe(chartElement);

    $('.btn-time').on('click', function (e) {
      $('.btn-time').removeClass('active')
      $(e.target).addClass('active')

      var startDate = $('#dateRange').data('daterangepicker').startDate.unix();
      var endDate = $('#dateRange').data('daterangepicker').endDate.unix();

      let filteredByDateData = []
      let timing = $(e.target).data('time');
      switch (timing) {
        case 'hour':
          filteredByDateData = hourData.filter((item) => item.time >= startDate && item.time <= endDate)
          seriesesData.set('1H', filteredByDateData)
          syncToInterval(intervals[0]);
          break;
        case 'day':
          filteredByDateData = dayData.filter((item) => item.time >= startDate && item.time <= endDate)
          seriesesData.set('1D', filteredByDateData)
          syncToInterval(intervals[1]);
          break;
        case 'week':
          syncToInterval(intervals[1]);
          break;
        case 'month':
          filteredByDateData = monthData.filter((item) => item.time >= startDate && item.time <= endDate)
          seriesesData.set('1M', filteredByDateData)
          syncToInterval(intervals[3]);
          break;
        case 'year':
          filteredByDateData = yearData.filter((item) => item.time >= startDate && item.time <= endDate)
          seriesesData.set('1Y', filteredByDateData)
          syncToInterval(intervals[4]);
          break;
      }
      fillTable(filteredByDateData)
    });

    $('.btn-csv').on('click', function (e) {
      var titles = [];
      var data = [];

      $('.table tr').each(function () {
        data.push($(this));
      });

      csv_data = []

      data.forEach(function (item, index) {
        td = item[0].children
        for (i = 0; i < td.length; i++) {
          csv_data.push(td[i].innerText)
        }
        csv_data.push('\r\n')
      })

      var downloadLink = document.createElement("a");
      var blob = new Blob(["\ufeff", csv_data]);
      var url = URL.createObjectURL(blob);
      downloadLink.href = url;
      downloadLink.download = "btc-price.csv";
      document.body.appendChild(downloadLink);
      downloadLink.click();
      document.body.removeChild(downloadLink);
    })

    function updateTable(startDate, endDate) {
      let timing = $('.btn-time.active').data('time') || 'year';
      let filteredByDateData = []
      switch (timing) {
        case 'hour':
          console.log(startDate)
          filteredByDateData = hourData.filter((item) => item.time >= startDate && item.time <= endDate)
          seriesesData.set('1H', filteredByDateData)
          syncToInterval(intervals[0]);
          break;
        case 'day':
          filteredByDateData = dayData.filter((item) => item.time >= startDate && item.time <= endDate)
          seriesesData.set('1D', filteredByDateData)
          syncToInterval(intervals[1]);
          break;
        case 'week':
          syncToInterval(intervals[1]);
          break;
        case 'month':
          filteredByDateData = monthData.filter((item) => item.time >= startDate && item.time <= endDate)
          seriesesData.set('1M', filteredByDateData)
          syncToInterval(intervals[3]);
          break;
        case 'year':
          filteredByDateData = yearData.filter((item) => item.time >= startDate && item.time <= endDate)
          seriesesData.set('1Y', filteredByDateData)
          syncToInterval(intervals[4]);
          break;
      }
      fillTable(filteredByDateData)
    }

    function fillTable(data) {
      $('.switcher').hide()
      $('#tblPrice').html('')
      data.forEach((item, index) => {
        $('#tblPrice').append(`<tr>
          <td>${moment.unix(item.time).format("MM/DD/YYYY HH:mm:ss")}</td>
          <td>$${parseFloat(item.value).toFixed(2)}</td>
        </tr>`)
      })
    }

    function onDateChange(start, end, label) {
      updateTable(start.unix(), end.unix())
    }

    $('#dateRange').daterangepicker({
      startDate: moment('2014-05-15')
    }, onDateChange);

    onDateChange(moment('2014-05-15'), moment('2023-07-10'))
  </script>
</body>

</html>