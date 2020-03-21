import { Line, mixins } from 'vue-chartjs'
const { reactiveProp, reactiveData } = mixins

export default {
    extends: Line,
    mixins: [reactiveProp],
    props: ['chartData', 'options'],
    mounted () {
        this.renderChart(this.chartData, this.options)
    },
    watch: {
        data: function() {
            console.log('render chart')
            this._chart.destroy()
            this.renderChart(this.chartData, this.options)
        }
    }
}