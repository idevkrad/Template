<template>
    <div class="card">
        <div class="card-body border-bottom py-3">
            <div class="d-flex align-items-center mt-n1 mb-n1">
                <ul class="list-unstyled hstack gap-3 mb-0 font-size-11 flex-grow-1">
                    <li class="fw-bold font-size-13">Daily Time Record (HR)</li>
                </ul>
                <div class="hstack gap-2">
                    <div class="input-group input-group-sm">
                    <input type="text" class="form-control " placeholder="Enter name/id no." v-model="keyword" style="width: 300px;">
                    <select v-model="month" @change="fetch()" class="form-select form-select-sm" style="width: 120px;">
                        <option value="0">January</option>
                        <option value="1">February</option>
                        <option value="2">March</option>
                        <option value="3">April</option>
                        <option value="4">May</option>
                        <option value="5">June</option>
                        <option value="6">July</option>
                        <option value="7">August</option>
                        <option value="8">September</option>
                        <option value="9">October</option>
                        <option value="10">November</option>
                        <option value="11">December</option>
                    </select>
                    <input type="text" class="form-control " placeholder="year" v-model="year" style="width: 60px;" @keyup="fetch()">
                    <label class="input-group-text">Month & Year</label>
                </div>
                    <!-- <button type="button" @click="generate($page.props.auth.data.id)" class="btn btn-primary btn-sm">Download DTR <i class="bx bx-download align-baseline ms-1"></i></button> -->
                    <button @click="generate()" type="button" v-b-tooltip.hover title="Print DTR" class="btn btn-primary btn-sm"><i class="bx bx-printer align-baseline"></i></button>
                </div>
            </div>
        </div>
        <div class="card-body font-size-12" :style="{ height: heightProfile + 'px' }" v-if="lists.length > 0">
            <table class="table table-bordered align-middle table-nowrap mb-0">
                <thead class="thead-light">
                    <tr class="text-center font-size-11"> 
                       <th width="20%">Date</th>
                        <th width="15%">Time In (am)</th>
                        <th width="15%">Time Out (am)</th>
                        <th width="15%">Time In (pm)</th>
                        <th width="15%">Time Out (pm)</th>
                        <th width="10%">Tardiness</th>
                        <th width="10%">Undertime</th>
                    </tr>
                </thead>
            </table>
            <div class="table-responsive" data-simplebar :style="{ height: (heightProfile-90) + 'px' }">
                <table class="table table-bordered align-middle table-nowrap mb-0">
                   <tbody>
                        <tr class="text-center" v-for="(list,index) in lists" v-bind:key="index" :class="list.bg" @click="update(list)" style="cursor: pointer;">
                        <td width="20%" class="text-start"><span class="text-body fw-bold">{{ list.date }}</span> <span class="text-muted font-size-11 ms-1">({{list.day}})</span></td>
                        <template v-if="!list.is_with">
                            <td width="80%" colspan="6" style="letter-spacing: 10px;">{{ list.data }}</td>
                        </template>
                        <template v-else>
                            <td v-if="!list.data.amabsent" class="fw-bold" :class="(list.data) ? list.data.amin : ''" width="15%">{{ (list.data) ? list.data.am_in : '' }}</td>
                            <td v-if="!list.data.amabsent" class="fw-bold" :class="(list.data) ? list.data.amout : ''" width="15%">{{ (list.data) ? list.data.am_out : '' }}</td>
                            <td v-if="list.data.amabsent" style="letter-spacing: 8px;" colspan="2" width="15%"><i>ABSENT</i></td>
                            <td v-if="!list.data.pmabsent" class="fw-bold" :class="(list.data) ? list.data.pmin : ''" width="15%">{{ (list.data) ? list.data.pm_in : '' }}</td>
                            <td v-if="!list.data.pmabsent" class="fw-bold" :class="(list.data) ? list.data.pmout : ''" width="15%">{{ (list.data) ? list.data.pm_out : '' }}</td>
                            <td v-if="list.data.pmabsent" style="letter-spacing: 8px;" colspan="2" width="15%"><i>ABSENT</i></td>
                            <th style="font-size: 9px;">{{ (list.data) ? list.data.tardiness : '' }}</th>
                            <th style="font-size: 9px;">{{ (list.data) ? list.data.undertime : '' }}</th>
                        </template>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-body font-size-12" :style="{ height: heightProfile + 'px' }" v-else>
            <div class="alert alert-warning mt-4 text-center" role="alert">
                Search username in the search box
            </div>
        </div>
    </div>
</template>
<script>
export default {
    inject:['count3', 'heightProfile'],
    data(){
        return {
            currentUrl: window.location.origin,
            lists: [],
            calendars : [],
            keyword: '',
            year: new Date().getFullYear(),
            month: new Date().getMonth(),
            editable: false,
            today: '',
            absent: 0,
            tardiness: 0,
            undertime: 0,
            list: {
                am_in_at : {},
                am_out_at: {},
                pm_in_at: {},
                pm_out_at: {}
            }
        }
    },

    watch: {
        keyword(newVal){
            if(newVal != ''){
                this.checkSearchStr(newVal);
            }else{
                this.lists = [];
            }
        },
        datares: {
            deep: true,
            handler(val = null) {
                if(val != null && val !== ''){
                    console.log(val.data);
                    this.message(val.data);
                }
            },
        }
    },

    computed: {
        datares() {
            return this.$page.props.flash.datares;
        },
    },

    methods : {
        checkSearchStr: _.debounce(function(string) {
            this.fetch();
        }, 300),

        fetch(page_url){
            page_url = page_url || '/dtr';
            axios.get(page_url,{
                params : {
                    keyword : this.keyword,
                    month: this.month,
                    year: this.year,
                    type: 'solo'
                }
            })
            .then(response => {
                if(response){
                    this.lists = response.data.data;
                    this.absent = response.data.absent;
                    this.tardiness = response.data.tardiness;
                    this.undertime = response.data.undertime;
                }
            })
            .catch(err => console.log(err));
        },

        generate(){
            this.$refs.generate.set(this.id);
        },

        update(list,index){
            this.index = index;
            this.$refs.update.set(list);
            this.editable = true;
        },

        message(list){
            this.lists[this.index].data = list;
            this.lists[this.index].bg = 'bg bg-success bg-soft';
        }
    }
}
</script>