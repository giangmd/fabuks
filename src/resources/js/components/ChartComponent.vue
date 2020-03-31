<template>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6">
                    Market
                </div>
                <div class="col-6 text-right">
                    Refesh automatic each 5 seconds.
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="chart-canvas">
                <line-chart
                    :chart-data="datacollection"
                    :options="options"
                />
            </div>
        </div>
    </div>
</template>
<script>
import LineChart from '../LineChart.js'

const cur_color = ['#41B884', '#F87979', '#273374', '#CCB1A2', '#96539F']
const limit_number_show = 1000
const time_recaculate = 5000
const min_rrand = -5
const max_rrand = 5
const limit_rrand = 10

export default {
    components: {
        LineChart
    },
    props: {
        curreniesData: {
            type: Array,
            default: null
        }
    },
    data() {
        return {
            datacollection: null,
            options: {
                height: 400,
                maintainAspectRatio: false,
            },
            timer_count: 0,
            limit_rrand: limit_rrand
        }
    },
    mounted() {
        this.fillData()
    },
    methods: {
        fillData() {

            axios.get('/api/v1/rate')
                .then(res => {
                    const datasets = []
                    const loc_data_rate = {}

                    res.data.rates.forEach(function(rate) {

                        // get color for border
                        let _color = cur_color[Math.floor(Math.random() * cur_color.length)]

                        // remove tmp this color
                        cur_color.splice(cur_color.indexOf(_color), 1)

                        let _label = rate.key
                        let rate_key = rate.key
                        if (rate.value > limit_number_show) {
                            rate.value = rate.value/limit_number_show
                            _label = _label + '(' + limit_number_show + ')'
                        }

                        // set item to datasets
                        let n_data = Number(rate.value)
                        n_data = n_data.toFixed(2)

                        loc_data_rate[rate_key] = n_data

                        let cur = {
                            id: rate_key,
                            label: _label,
                            backgroundColor: false,
                            fill: false,
                            borderColor: _color,
                            data: [n_data]
                        }

                        datasets.push(cur)
                    })

                    this.datacollection = {
                        labels: [0],
                        datasets: datasets,
                    }

                    localStorage.setItem('data_rate', JSON.stringify(loc_data_rate))

                    this.timerInterval()
                }).catch(err => {
                    console.log('/api/v1/rate')
                    console.log(err)
                })
        },
        recaculateRate() {
            const self = this

            this.timer_count++

            const old_datacollection = this.datacollection

            const new_labels = old_datacollection.labels
            new_labels.push(this.timer_count * (time_recaculate/1000))

            if (new_labels.length > 30) {
                new_labels.splice(0, 1)
            }

            const new_datasets = []
            const loc_data_rate = {}

            old_datacollection.datasets.forEach(function(obj, index) {
                let old_ary = obj.data

                if (old_ary.length > 30) {
                    old_ary.splice(0, 1)
                }

                let new_ary = old_ary

                let last_rate = old_ary[old_ary.length - 1]
                last_rate = Number(last_rate)

                // make new random rate
                let new_rate = 0
                let new_rrate = Math.random() * (max_rrand - min_rrand) + min_rrand
                new_rrate = Number(new_rrate.toFixed(2))

                let new_target = new_rrate + last_rate

                // let equa_rate = new_rrate - last_rate
                if (new_target >= limit_rrand) {

                    // console.log('price down')

                    // reset new limit rate
                    self.limit_rrand = self.limit_rrand - new_rrate

                    // rate go down
                    new_target = last_rate - new_rrate
                } else if (new_target < limit_rrand) {

                    // console.log('price up')

                    // reset new limit rate
                    self.limit_rrand = self.limit_rrand + new_rrate

                    // rate go up
                    new_target = last_rate + new_rrate
                }

                new_ary.push(new_target)

                let rate_key = obj.id
                loc_data_rate[rate_key] = new_target

                // set item to datasets
                let cur = {
                    id: rate_key,
                    label: obj.label,
                    backgroundColor: false,
                    fill: false,
                    borderColor: obj.borderColor,
                    data: new_ary
                }

                new_datasets.push(cur)
            })

            this.datacollection = {
                labels: new_labels,
                datasets: new_datasets,
            }

            localStorage.setItem('data_rate', JSON.stringify(loc_data_rate))

        },
        timerInterval() {
            const self = this
            setInterval(function() {
                self.recaculateRate()
            }, time_recaculate)
        }
    }
}
</script>

<style>
    .chart-canvas {
        
    }
</style>