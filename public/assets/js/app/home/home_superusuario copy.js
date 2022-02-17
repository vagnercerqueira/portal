$(document).ready(function () {
    // Create the chart
    Highcharts.chart('container', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Serviços julho de 2020'
        },
        subtitle: {
            text: ''
        },
        accessibility: {
            announceNewData: {
                enabled: true
            }
        },
        xAxis: {
            type: 'category'
        },
        yAxis: {
            title: {
                text: 'Ranking de serviços'
            }

        },
        legend: {
            enabled: false
        },
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '{point.y:.1f}%'
                }
            }
        },

        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
        },

        series: [
            {
                name: "Serviços",
                colorByPoint: true,
                data: [
                    {
                        name: "BALANCEAMENTO",
                        y: 62.74,
                        drilldown: "BALANCEAMENTO"
                    },
                    {
                        name: "CAMBAGEM",
                        y: 7.23,
                        drilldown: "CAMBAGEM"
                    },
                    {
                        name: "CONSERTO PNEU",
                        y: 5.58,
                        drilldown: "CONSERTO PNEU"
                    },
                    {
                        name: "CONVERGENCIA",
                        y: 4.02,
                        drilldown: "CONVERGENCIA"
                    }
                ]
            }
        ],
        drilldown: {
            series: [
                {
                    name: "BALANCEAMENTO",
                    id: "BALANCEAMENTO",
                    data: [
                        [
                            "BALANCEAMENTO SUV",
                            0.1
                        ],
                        [
                            "BALANCEAMENTO MED",
                            1.3
                        ],
                        [
                            "BALANCEAMENTO PEQ",
                            53.02
                        ],
                        [
                            "BALANCEAMENTO FT",
                            1.4
                        ]
                    ]
                },
                {
                    name: "CAMBAGEM",
                    id: "CAMBAGEM",
                    data: [
                        [
                            "CAMBAGEM",
                            7.23
                        ]
                    ]
                },
                {
                    name: "CONSERTO PNEU",
                    id: "CONSERTO PNEU",
                    data: [
                        [
                            "CONSERTO PNEU",
                            5.58
                        ]
                    ]
                },
                {
                    name: "CONVERGENCIA",
                    id: "CONVERGENCIA",
                    data: [
                        [
                            "CONVERGENCIA DIANT",
                            3.39
                        ],
                        [
                            "CONVERGENCIA DIANT GAR",
                            0.96
                        ],
                        [
                            "CONVERGENCIA DIANT FT",
                            0.36
                        ],
                        [
                            "CONVERGENCIA DIANT PEQ",
                            0.54
                        ]
                    ]
                }
            ]
        }
    });



    // Create the chart
    Highcharts.chart('container2', {
        chart: {
            type: 'pie'
        },
        title: {
            text: 'Faturamento julho 2020'
        },
        subtitle: {
            text: ''
        },

        accessibility: {
            announceNewData: {
                enabled: true
            },
            point: {
                valueSuffix: 'R$'
            }
        },

        plotOptions: {
            series: {
                dataLabels: {
                    enabled: true,
                    format: '{point.name}: R${point.y:.1f}'
                }
            }
        },

        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>R${point.y:.2f}</b> total<br/>'
        },

        series: [
            {
                name: "Faturamento",
                colorByPoint: true,
                data: [
                    {
                        name: "BALANCEAMENTO",
                        y: 175.82,
                        drilldown: "BALANCEAMENTO"
                    },
                    {
                        name: "CAMBAGEM",
                        y: 57.23,
                        drilldown: "CAMBAGEM"
                    },
                    {
                        name: "CONSERTO PNEU",
                        y: 125.58,
                        drilldown: "CONSERTO PNEU"
                    },
                    {
                        name: "CONVERGENCIA",
                        y: 95.25,
                        drilldown: "CONVERGENCIA"
                    }
                ]
            }
        ],
        drilldown: {
            series: [
                {
                    name: "BALANCEAMENTO",
                    id: "BALANCEAMENTO",
                    data: [
                        [
                            "BALANCEAMENTO SUV",
                            60.1
                        ],
                        [
                            "BALANCEAMENTO MED",
                            11.3
                        ],
                        [
                            "BALANCEAMENTO PEQ",
                            53.02
                        ],
                        [
                            "BALANCEAMENTO FT",
                            51.4
                        ]
                    ]
                },
                {
                    name: "CAMBAGEM",
                    id: "CAMBAGEM",
                    data: [
                        [
                            "CAMBAGEM",
                            57.23
                        ]
                    ]
                },
                {
                    name: "CONSERTO PNEU",
                    id: "CONSERTO PNEU",
                    data: [
                        [
                            "CONSERTO PNEU",
                            125.58
                        ]
                    ]
                },
                {
                    name: "CONVERGENCIA",
                    id: "CONVERGENCIA",
                    data: [
                        [
                            "CONVERGENCIA DIANT",
                            23.39
                        ],
                        [
                            "CONVERGENCIA DIANT GAR",
                            10.96
                        ],
                        [
                            "CONVERGENCIA DIANT FT",
                            20.36
                        ],
                        [
                            "CONVERGENCIA DIANT PEQ",
                            40.54
                        ]
                    ]
                }
            ]
        }
    });
});