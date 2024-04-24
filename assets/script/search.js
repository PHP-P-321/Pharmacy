$(document).ready(function() {
    $('#searchButton').click(function() {
        var searchText = $('#searchInput').val();
        var selectedWarehouses = $('#warehouseSelect').val();
        var warehousesData = $('#warehouseSelect').data('warehouses');
        var warehouses = JSON.parse(warehousesData);
        var allWarehouseNames = warehouses.map(function(warehouse) {
            return warehouse.name; // Получаем массив названий складов
        });
        
        $.ajax({
            url: './vendor/search-medications.php',
            type: 'POST',
            data: {
                search: searchText,
                warehouses: selectedWarehouses,
                allWarehouses: allWarehouseNames // Передаем названия всех складов
            },
            success: function(response) {
                $('tbody').empty();
                
                var data = JSON.parse(response);
                
                data.forEach(function(item) {
                    var row = '<tr>' +
                                  '<td>' + item.name_medication + '</td>';
                    
                    var quantities = item.quantity_medication.split(', ');
                    var warehouseIds = item.id_warehouse.split(', ');
                    
                    warehouseIds.forEach(function(warehouseId) {
                        var index = allWarehouseNames.indexOf(warehouseId); // Поиск индекса склада
                        if (index !== -1) {
                            row += '<td>' + (quantities[index] || 'нет на складе') + '</td>';
                        } else {
                            row += '<td>нет на складе</td>';
                        }
                    });
                    
                    row += '</tr>';
                    $('tbody').append(row);
                });
            },
            error: function() {
                alert('Ошибка при выполнении AJAX запроса');
            }
        });
    });
});
