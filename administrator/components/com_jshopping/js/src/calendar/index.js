const excludeDays = {
    extra_weekend_days: [...JSON.parse(Joomla.getOptions('extra_weekend_days'))],
    extra_working_days: [...JSON.parse(Joomla.getOptions('extra_working_days'))]
}

const calendar = new FullCalendar.Calendar(document.querySelector('#calendar'),
{
    locale: Joomla.getOptions('lang-tag'),
    height: 650,
    plugins: ['dayGrid'],
    events:[{
        id: 'working',
        daysOfWeek: [...JSON.parse(Joomla.getOptions('working-days'))],
        allDay: true,
    }, {
        id: 'weekend',
        daysOfWeek: [...JSON.parse(Joomla.getOptions('weekends'))],
        allDay: true,
        color: 'white'
    }],
    eventClick: info => {
        const { el, event } = info
        let text, color
        
        const date = event.start;
        const now = convertingDay(date);
        const today = convertingDay(new Date());

        if (el.dataset.deleted) {
            text = Joomla.JText._('COM_SMARTSHOP_ADD_EVENT')
            color = '#3788d8'
        } else {
            text = Joomla.JText._('COM_SMARTSHOP_DELETE_EVENT')
            color = '#fff'
        }

        if (confirm(text)) {
            if (el.dataset.deleted) {
                delete el.dataset.deleted

                if (event.id == 'weekend') {
                    excludeDays.extra_working_days.push(now)
                } else {
                    excludeDays.extra_weekend_days = excludeDays.extra_weekend_days.filter(d => d != now);
                }

            } else {
                el.dataset.deleted = true

                if (event.id == 'working') {
                    excludeDays.extra_weekend_days.push(now)
                } else {
                    excludeDays.extra_working_days = excludeDays.extra_working_days.filter(d => d != now);
                }

                
            }

            setEventColor(el, color)
            
            if (event.id == 'working' && el.dataset.deleted ||
                event.id == 'weekend' && el.dataset.deleted
            ) {
                if (today == now) { setEventColor(el, 'transparent') }
            }
            
        }

    },
    eventRender: info => {
        const { el, event } = info
        
        const date = event.start;
        const now = convertingDay(date);
        const today = convertingDay(new Date());

        if (event.id == 'weekend') {
            if (excludeDays.extra_working_days.includes(now)) {
                delete el.dataset.deleted
                setEventColor(el, '#3788d8')
            } else {
                el.dataset.deleted = true
                if (today == now) { setEventColor(el, 'transparent') }
            }
        } else {
            if (excludeDays.extra_weekend_days.includes(now)) {
                el.dataset.deleted = true
                setEventColor(el, '#fff')
                if (today == now) { setEventColor(el, 'transparent') }
            }
        }

    },
    customButtons: {
        setWorkTime: {
            text: Joomla.JText._('COM_SMARTSHOP_SET_WORK_TIME'),
            click: () => {
				document.querySelector('.btn__changeAddress').click();
                    //dispatchEvent(new Event('click'));
            }
        }
    },
    header: {
        left: 'setWorkTime',
        center: 'title',
        right: ' today, prev, next'
    },
    
});

// init calendar
calendar.render()

function changeWorkTime(days) {
    // Removing WorkTime
    calendar.getEvents().forEach(e => e.remove())
    
    // Adding WorkTime
    calendar.batchRendering(() => {
        calendar.addEvent({
            id: 'working',
            daysOfWeek: [...days],
            allDay: true,
        });
    })
     
    document.querySelector('input[name="working_days"]').value = JSON.stringify(days)
}

function setEventColor(el, color) {
    el.style.borderColor = color
    el.style.backgroundColor = color
}

function convertingDay(date) {
    let month = date.getMonth() + 1;
    let day = date.getDate();
    
    if (month < 10) month = '0' + month
    if (day < 10) day = '0' + day

    return `${date.getFullYear()}-${month}-${day}`;
}

Joomla.submitbutton = task => {

    if (task == 'save' || task == 'apply') {
        document.querySelector('input[name="extra_weekend_days"]').value = JSON.stringify(excludeDays.extra_weekend_days)
        document.querySelector('input[name="extra_working_days"]').value = JSON.stringify(excludeDays.extra_working_days)
    }
    
    Joomla.submitform(task, document.getElementById('adminForm'));
 }