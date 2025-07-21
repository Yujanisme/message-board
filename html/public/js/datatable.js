function initDataTable(table, url, columns, options = {}) {
    const dataTable = {
        processing: true,
            serverSide: true,
            searching: false,
            destroy: true ,
            ajax: {
                url: url,
                type: 'GET',
                data: function(d) {
                    for (const key in options) {
                    const val = typeof options[key] === 'function' ? options[key]() : options[key];
                    d[key] = val;
                }
                }
            },
            columns: columns,
            language: {
                lengthMenu: "顯示 _MENU_ 筆資料",
                paginate: {
                    previous: "上一頁",
                    next: "下一頁",
                    first: "第一頁",
                    last: "最後一頁"
                },
                info: "顯示第 _START_ 至 _END_ 筆資料，共 _TOTAL_ 筆",
                infoEmpty: "目前沒有資料",
                infoFiltered: "(從總共 _MAX_ 筆中篩選)"
            }
    }
    const settings = Object.assign({}, dataTable, options);
    return $(table).DataTable(settings);
}