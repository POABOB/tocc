
const identity = ["就診", "陪同", "其他"];
const travel_histroy = ["無", "曾出國", "曾至國內旅遊"];
const occupation = ["無", "醫院工作者", "交通運輸業", "旅遊業", "旅館業", "航空服務業", "其他"];
const contact_history = ["無", "曾至醫院、診所就醫", "曾接觸至國外旅遊且有發燒/呼吸道症狀之親友/家屬", "曾出入機場、觀光景點及其他頻繁接觸外國人場所", "曾參與公眾集會", "野生動物與禽鳥接觸", "其他"];
const contact_multi = ["宗教/政治/學術/藝文活動", "開學/畢業典禮、婚喪喜慶、運動賽事等聚眾活動"];
const cluster = ["無", "同住家人正在", "家人也有發燒或呼吸道症狀", "朋友也有發燒或呼吸道症狀", "同事也有發燒或呼吸道症狀"];
const cluster_multi = ["居家隔離", "居家檢疫", "自主健康管理"];

function getList () {
    // var date = new Date($('#date').val());
    // var month = date.getMonth() + 1;
    // var year = date.getFullYear();
    // const m = `${year}-${padLeft(month)}`;
    const m = $('#date').val();
    axios.get(`/api/list?cusid=${customer_data.cusid}&month=${m}`)
        .then(function (response) {
            renderList(response.data.data);
            console.log(response);
        })
        .catch(function (error) {
            console.log(error);
        });
}
function renderList (data) {
    $("#list > tbody > tr").remove();
    $("#list > tbody").append('<tr></tr>');
    data.forEach(item => {
        $('.list tbody tr:last').after(
            `<tr>
                <td>${item.created_at}</td>
                <td>${item.name}</td>
                <td>${item.cellphone}</td>
                <td>${identity[item.identity]}</td>
                <td>${item.residence}</td>
                <td>${travel_histroy[item.travel_histroy]}(${item.travel_country || item.travel_destination})</td>
                <td>${occupation[item.occupation]}(${item.occupation_other})</td>
                <td>${getContactHistory(contact_history, item, JSON.parse(item.contact_history))}</td>
                <td>${getCluster(cluster, item, JSON.parse(item.cluster))}</td>
            </tr>`
        );
    })
}
function getContactHistory (refer, row, value) {
    let str = '';
    value.forEach(item => {
        str +=  '.' + refer[item];
        if(refer[item] == '其他'){
            str += `(${row.contact_other})<br>`;
        }
        if(refer[item] == '曾參與公眾集會'){
            str += `(${contact_multi[row.contact_multi]})<br>`;
        }
    })
    return str;
}
function getCluster (refer, row, value) {
    let str = '';
    value.forEach(item => {
        str +=  '.' + refer[item];
        if(refer[item] == '同住家人正在'){
            str += `(${cluster_multi[row.cluster_multi]})<br>`;
            if(cluster_multi[row.cluster_multi] == '自主健康管理'){
                str += `(到期日：${row.cluster_date})<br>`;
            }
        }
    })
    return str;
}
function padLeft (num) {
    let m = parseInt(num);
    if(m < 10) {
        m = `0${m}`;
    }
    return `${m}`;
}
function getDate () {
    var today = new Date();
    var y = today.getFullYear();
    var m = padLeft(today.getMonth()+1);
    var d = padLeft(today.getDate());
    var date = y+'-'+m+'-'+d;
    $('#date').val(date);
}
