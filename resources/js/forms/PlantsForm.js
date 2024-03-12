export default class PlantsForm {

    onClickAddNewTask(addTaskButton) {

        addTaskButton.on('click', () => {

            let template = $('#add-new-task-template').html();
            let newRow = $(template).appendTo($('#task-container'));

            this.onClickRemoveTask(newRow.find('.remove-task'));
            this.onChangeCheckEndMonth(newRow.find('.task-start-month-select'));
            this.onChangeCheckStartMonth(newRow.find('.task-end-month-select'));
        });
    };
    onClickRemoveTask(removeTaskButton) {

        removeTaskButton.on('click', function () {
            $(this).parents('.task-container').remove();
        });
    }
    onChangeCheckEndMonth(startMonthSelect) {

        startMonthSelect.on('change', function() {

            let endMonthElement  = $(this).parent().find('.task-end-month-select');

            let startValue  = $(this).find(":selected").val();
            let endValue    = endMonthElement.find(":selected").val();

            if(startValue > endValue) {
                endMonthElement.val(startValue);
            }
        });
    }
    onChangeCheckStartMonth(endMonthSelect) {

        endMonthSelect.on('change', function() {

            let startMonthElement  = $(this).parent().find('.task-start-month-select');

            let startValue  = startMonthElement.find(":selected").val();
            let endValue    = $(this).find(":selected").val();

            if(startValue > endValue) {
                startMonthElement.val(endValue);
            }
        });
    }
}
