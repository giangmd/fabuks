<template>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6">
                    Exchange
                </div>
                <div
                    v-if="isButtonDisabled"
                    class="col-6 text-right"
                >
                    Next offer in <span class="time-left">{{ timeLeft }}</span>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-9">
                    <b-form
                        v-if="show"
                        @submit="onSubmit"
                    >
                        <b-row>
                            <b-col>
                                <b-form-group
                                    id="input-group-3"
                                    label="From:"
                                    label-for="input-3"
                                >
                                    <b-form-select
                                        id="input-3"
                                        v-model="form.from"
                                        :options="from_currenies"
                                        required
                                        @change="onFromChange"
                                    />
                                </b-form-group>
                            </b-col>
                            <b-col>
                                <b-form-group
                                    id="input-group-4"
                                    label="To:"
                                    label-for="input-4"
                                >
                                    <b-form-select
                                        id="input-4"
                                        v-model="form.to"
                                        :options="to_currenies"
                                        required
                                    />
                                </b-form-group>
                            </b-col>
                        </b-row>

                        <b-form-group
                            id="input-group-1"
                            label="Amount:"
                            label-for="input-1"
                        >
                            <b-form-input
                                id="input-1"
                                v-model="form.amount"
                                type="number"
                                required
                                step="0.01"
                                min="0.00"
                                max="1000.00"
                            />
                        </b-form-group>

                        <b-button
                            type="submit"
                            variant="primary"
                            :disabled="isButtonDisabled"
                        >
                            Place The Offer
                        </b-button>
                    </b-form>
                </div>
                <div class="col-3">
                    <h5>Wallet</h5>
                    <b-list-group>
                        <b-list-group-item
                            v-for="cur in curs" 
                            :key="cur.key"
                        >
                            {{ cur.key }} : {{ cur.value }}
                        </b-list-group-item>
                    </b-list-group>
                </div>
            </div>
            
            <div class="mt-4">
                <b-card
                    class="mt-3"
                    border-variant="success"
                    header="Trade History"
                >
                    <b-table
                        striped
                        hover
                        :items="trade_history"
                        :fields="trade_fields"
                    />
                </b-card>
            </div>
        </div>
    </div>
</template>
<script>
var time_refetch = 10000 //10 seconds
var time_accept_renew = 300 // 5 minutes, will *1000 in countdown
var intervalTimer;

export default {
    components: {
            
    },
    props: {
        curreniesData: {
            type: Array,
            default: null
        },
        fabukData: {
            type: Array,
            default: null
        }
    },
    data() {
        return {
            timeLeft: '00:00',
            isButtonDisabled: false,
            currenies: null,
            from_currenies: [{ text: 'Select One', value: null }],
            to_currenies: [{ text: 'Select One', value: null }],
            form: {
                from: null,
                to: null,
                amount: 1,
                price_order: 0,
            },
            show: true,
            trade_history: [],
            trade_fields: ['from', 'to', 'amount', 'price', {key: 'status', sortable: true}, 'time'],
            curs: [],
        }
    },
    mounted() {
        let _c = this.curreniesData
        _c.unshift(this.fabukData)

        this.currenies = _c
        this.from_currenies = _c
        this.to_currenies = _c

        this.fetchTradeHistory()
    },
    methods: {
        fetchTradeHistory() {
            /**
                 * This function call get exists history trade
                 */
            const self = this
            axios.get('/api/v1/trade')
                .then(res => {
                    if (typeof res.data.trade.data !== 'undefined') {
                        let trades = res.data.trade.data

                        trades.forEach(function(trade) {
                            let _d = new Date(trade.created_at)
                            _d = self.formatDate(_d)

                            let new_trade = {
                                from: trade.from,
                                to: trade.to,
                                amount: trade.amount,
                                price: parseFloat(trade.price_order).toFixed(2),
                                status: (trade.status == 0) ? '...Pendding' : 'Success',
                                time: _d,
                            }

                            self.trade_history.unshift(new_trade)
                        })

                    }

                    if (typeof res.data.accept_new !== 'undefined') {
                        if (res.data.accept_new) {
                            self.isButtonDisabled = false
                        } else {
                            self.isButtonDisabled = true

                            self.callCountDownTime(time_accept_renew)
                        }
                    }

                    if (typeof res.data.user_balance !== 'undefined') {
                        self.displayCurrencyLeft(res.data.user_balance)
                    }

                    self.timerInterval()
                }).catch(err => {
                    console.log('/api/v1/trade')
                    console.log(err)
                })
        },
        onSubmit(evt) {
            /**
                 * This function call exchange submit form
                 */

            const self = this
            evt.preventDefault()

            let _form_data = this.form
            let data_rate = {}
            if (localStorage.getItem('data_rate')) {
                data_rate = JSON.parse(localStorage.getItem('data_rate'))
            }

            if (this.form.from == this.fabukData) {
                this.form.price_order = data_rate[this.form.to]
            } else if (this.form.to == this.fabukData) {
                this.form.price_order = data_rate[this.form.from]
            }

            axios.post('/api/v1/trade/offer', this.form)
                .then(res => {

                    if (typeof res.data.alert !== 'undefined') {
                        alert(res.data.alert)
                        return false
                    } else if ((typeof res.data.trade !== 'undefined') && (typeof res.data.trade.id !== 'undefined')) {
                        let trade = res.data.trade
                        let _d = new Date(trade.created_at)
                        _d = self.formatDate(_d)

                        let new_trade = {
                            from: trade.from,
                            to: trade.to,
                            amount: trade.amount,
                            price: parseFloat(trade.price_order).toFixed(2),
                            status: (trade.status == 0) ? '...Pendding' : 'Success',
                            time: _d,
                        }

                        self.trade_history.unshift(new_trade)
                    }

                    if (typeof res.data.accept_new !== 'undefined') {
                        if (res.data.accept_new) {
                            self.isButtonDisabled = false
                        } else {
                            self.isButtonDisabled = true

                            self.callCountDownTime(time_accept_renew)
                        }
                    }

                    if (typeof res.data.user_balance !== 'undefined') {
                        self.displayCurrencyLeft(res.data.user_balance)
                    }
                }).catch(err => {
                    console.log('/api/v1/trade/offer')
                    console.log(err)
                })
        },
        onFromChange(val) {
            /**
                 * This function call on select box From have been fired change event
                 */

            const self = this

            if (val !== this.fabukData) {
                this.to_currenies = [this.fabukData]
            } else {
                let _t = []
                this.currenies.forEach(function(val) {
                    if (val !== self.fabukData) {
                        _t.push(val)
                    }
                })
                this.to_currenies = _t
            }
        },
        reFetchTradeHistory() {
            /**
                 * This function call repeat resolve trade offer status
                 * Repeat in interval function
                 */

            const self = this
            let input_data = {}
            if (localStorage.getItem('data_rate')) {
                input_data.data_rate = JSON.parse(localStorage.getItem('data_rate'))
            }

            axios.post('/api/v1/trade/refetch', input_data)
                .then(res => {

                    if (typeof res.data.accept_new !== 'undefined') {
                        if (res.data.accept_new) {
                            self.isButtonDisabled = false
                        } else {
                            self.isButtonDisabled = true
                        }
                    }

                    if (typeof res.data.trade.data !== 'undefined') {
                        let new_trade_history = []
                        let trades = res.data.trade.data

                        trades.forEach(function(trade) {
                            let _d = new Date(trade.created_at)
                            _d = self.formatDate(_d)

                            let new_trade = {
                                from: trade.from,
                                to: trade.to,
                                amount: trade.amount,
                                price: parseFloat(trade.price_order).toFixed(2),
                                status: (trade.status == 0) ? '...Pendding' : 'Success',
                                time: _d,
                            }

                            new_trade_history.push(new_trade)
                        })

                        // refresh trade list
                        self.trade_history = new_trade_history
                    }

                    if (typeof res.data.user_balance !== 'undefined') {
                        self.displayCurrencyLeft(res.data.user_balance)
                    }

                }).catch(err => {
                    console.log('/api/v1/trade/offer')
                    console.log(err)
                })
        },
        displayCurrencyLeft(currenies) {
            let new_curs = []
            let _total = null
            currenies.forEach(function(c) {
                if (c.type != 'total') {
                    new_curs.push({
                        key: c.type,
                        value: c.balance
                    })
                } else {
                    _total = c.balance
                }
            })

            if (typeof _total !== null) {
                document.getElementById("total_balance").innerHTML = _total
            }

            this.curs = new_curs
        },
        timerInterval() {
            const self = this
            setInterval(function() {
                self.reFetchTradeHistory()
            }, time_refetch)
        },
        callCountDownTime(seconds) {
            const now = Date.now();
            const end = now + seconds * 1000;

            this.displayTimeLeft(seconds);
            this.countdown(end);
        },
        countdown(end) {
            const self = this
            intervalTimer = setInterval(() => {
                const secondsLeft = Math.round((end - Date.now()) / 1000);

                if(secondsLeft == 0) {
                    self.isButtonDisabled = false
                }

                if(secondsLeft < 0) {
                    clearInterval(intervalTimer);
                    return;
                }

                this.displayTimeLeft(secondsLeft)
            }, 1000);
        },
        displayTimeLeft(secondsLeft) {
            const minutes = Math.floor((secondsLeft % 3600) / 60);
            const seconds = secondsLeft % 60;

            this.timeLeft = `${this.zeroPadded(minutes)}:${this.zeroPadded(seconds)}`
        },
    }
}
</script>

<style type="text/css">
    .time-left {
        font-size: 22px;
    }
</style>
