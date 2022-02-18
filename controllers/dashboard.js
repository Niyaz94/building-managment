$(document).ready(function() {
    require.config({
        paths: {
            echarts: '../API/assets/js/plugins/visualization/echarts'
        }
    });
    data=[];
    $.ajax({
        url: "models/_dashboard.php",
        type: "POST",
        data: {
            "type":"given"
        },
        dataType: "json",
        complete: function () {
            oneCloseLoader("#"+$(this).parent().id,"self");
        },
        beforeSend: function () {
            oneOpenLoader("#"+$(this).parent().id,"self","dark");
        },
        success: function (res) {
            data=res;
        },
        fail: function (err){
            oneAlert("error","Error!!!",res.data)
        },
        always:function(){
            console.log("complete");
        }
    });
    setTimeout(function(){
        familyGiven(data[0]);       
    },1000)    
});
function familyGiven(data){
    paid=[0,0,0];
    parialpaid=[0,0,0];
    notpaid=[0,0,0];
    for (let index = 0; index < data.length; index++) {
        if(data[index]["label"]=="Area_NotPaid"){
            notpaid[0]=data[index]["total"];
        }else if(data[index]["label"]=="Area_PartialPaid"){
            parialpaid[0]=data[index]["total"];            
        }else if(data[index]["label"]=="Area_Paid"){
            paid[0]=data[index]["total"];
        }else if(data[index]["label"]=="Service_NotPaid"){
            notpaid[1]=data[index]["total"];            
        }else if(data[index]["label"]=="Service_PartialPaid"){
            parialpaid[1]=data[index]["total"];                        
        }else if(data[index]["label"]=="Service_Paid"){
            paid[1]=data[index]["total"];            
        }else if(data[index]["label"]=="Electric_NotPaid"){
            notpaid[2]=data[index]["total"];            
        }else if(data[index]["label"]=="Electric_PartialPaid"){
            parialpaid[2]=data[index]["total"];                        
        }else if(data[index]["label"]=="Electric_Paid"){
            paid[2]=data[index]["total"];            
        }
    }
    require(
        [
            'echarts',
            'echarts/theme/limitless',
            'echarts/chart/bar',
            'echarts/chart/line'
        ],
        function (ec, limitless) {
            var basic_columns = ec.init(document.getElementById('basic_columns'), limitless);
            basic_columns_options = {
                grid: {x: 40,x2: 40,y: 35,y2: 25},
                tooltip: {trigger: 'axis'},
                legend: {data: ['Paid', 'Paritial Paid',"Not Paid"]},
                calculable: true,
                xAxis: [{
                    type: 'category',
                    data: ["Paid","Partial Paid","Not Paid"]
                }],
                yAxis: [{type: 'value'}],
                series: [
                    {
                        name: 'Paid',
                        type: 'bar',
                        data: paid,
                        itemStyle: {
                            normal: {
                                color: 'blue',
                                label: {
                                    show: true,
                                    textStyle: {
                                        fontWeight: 500
                                    }
                                }
                            }
                        }
                    },{
                        name: 'Paritial Paid',
                        type: 'bar',
                        data: parialpaid,
                        itemStyle: {
                            normal: {
                                color: 'green',
                                label: {
                                    show: true,
                                    textStyle: {
                                        fontWeight: 500
                                    }
                                }
                            }
                        }
                    },{
                        name: 'Not Paid',
                        type: 'bar',
                        data: notpaid,
                        itemStyle: {
                            normal: {
                                color: 'red',
                                label: {
                                    show: true,
                                    textStyle: {
                                        fontWeight: 500
                                    }
                                }
                            }
                        }
                    }
                ]
            };
            basic_columns.setOption(basic_columns_options);
            
        }
    );
}