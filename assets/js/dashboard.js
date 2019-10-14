require('../css/dashboard.scss');

function drawCharts()
{
    let containers = document.querySelectorAll('.dashboard-chart');
    let google = window.google;

    for (let property in containers) {

        if (!containers.hasOwnProperty(property)) continue;

        let container = containers[property];
        let data = new google.visualization.DataTable();
        data.addColumn(container.getAttribute('data-x-column-type'), container.getAttribute('data-x-column-label'));
        data.addColumn(container.getAttribute('data-y-column-type'), container.getAttribute('data-y-column-label'));

        let options = {
            height: 200,
            locale: 'pt_BR',
            vAxis: {
                format: container.getAttribute('data-x-column-format')
            },
            hAxis: {
                format: container.getAttribute('data-y-column-format')
            },
            legend: {position: 'none'},
            chartArea: {
                left: 50,
                top: 20,
                bottom: 30,
                right: 20
            }
        };

        let chart = new google.visualization.LineChart(container);

        let request = new XMLHttpRequest();
        request.open('GET', container.getAttribute('data-set-path'));
        request.addEventListener('readystatechange', function () {

            console.log(request.readyState, request.DONE);

            if (request.readyState === request.DONE) {

                if (request.status === 200) {

                    let dataSet = JSON.parse(request.responseText);

                    console.log(dataSet);

                    for (let index in dataSet) {

                        if (!dataSet.hasOwnProperty(index)) continue;

                        dataSet[index][0] = new Date(dataSet[index][0]);
                    }

                    data.addRows(dataSet);
                    chart.draw(data, options);

                    window.addEventListener('resize', function () {
                        chart.draw(data, options);
                    })

                }
            }
        });
        request.send();
    }
}

let script = document.createElement('script');
script.type = 'text/javascript';
script.src = 'https://www.gstatic.com/charts/loader.js';

script.addEventListener('load', function () {
    let google = window.google;

    google.charts.load('current', {'packages': ['corechart'], 'language': 'pt-BR'});
    google.charts.setOnLoadCallback(drawCharts);
});

document.head.appendChild(script);
