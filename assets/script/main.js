// Ждем, пока весь HTML-документ будет загружен и DOM будет доступен для манипуляций
document.addEventListener('DOMContentLoaded', function() {
    // Получаем ссылки на элементы HTML, с которыми будем работать
    var warehouseFromSelect = document.getElementById('warehouseFromSelect');
    var warehouseToSelect = document.getElementById('warehouseToSelect');
    var quantityInput = document.getElementById('quantityInput');
    var medicationSelect = document.getElementById('medicationSelect');

    // Функция для обновления текста placeholder в поле ввода количества
    function updatePlaceholder() {
        // Получаем значения выбранных элементов из выпадающих списков
        var selectedMedicationId = medicationSelect.value;
        var selectedWarehouseId = warehouseFromSelect.value;

        // Находим информацию о выбранном препарате из массива medicationsData
        var medication = medicationsData.find(function(item) {
            return item[0] === selectedMedicationId;
        });

        // Если информация о препарате найдена
        if (medication) {
            // Разбиваем строки с количествами и id складов на отдельные значения
            var quantities = medication[3].split(', ');
            var warehouseIds = medication[1].split(', ');

            // Находим индекс выбранного склада в массиве id складов
            var index = warehouseIds.indexOf(selectedWarehouseId);
            // Если склад найден
            if (index !== -1) {
                // Устанавливаем текст placeholder в соответствии с количеством препарата на складе
                quantityInput.placeholder = quantities[index];
            } else {
                // Если выбранный склад не найден, устанавливаем текст placeholder "Нет на складе"
                quantityInput.placeholder = 'Нет на складе';
            }
        }
    }

    // Назначаем обработчики событий на изменение выбранных элементов
    warehouseFromSelect.addEventListener('change', updatePlaceholder);
    warehouseToSelect.addEventListener('change', updatePlaceholder);
    medicationSelect.addEventListener('change', updatePlaceholder);

    // Вызываем функцию updatePlaceholder для установки начального значения placeholder
    updatePlaceholder();
});
