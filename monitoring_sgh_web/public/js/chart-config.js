const adapters = Chart._adapters;
adapters._date.override({
    formats: function (type, time) {
        if (!time) return 'MMM D, YYYY';
        if (type === 'time' && time.displayFormat === 'auto') {
            return 'MMM D, YYYY h:mm:ss a';
        }
        return time.displayFormat || 'MMM D, YYYY';
    },
    parse: function (value, format) {
        if (format === 'MMMM' || format === 'MMM') {
            value += ' 1';
        }
        return moment(value, format);
    },
    format: function (time, format) {
        return time.format(format);
    },
    add: function (time, amount, unit) {
        return time.add(amount, unit);
    },
    diff: function (max, min, unit) {
        return max.diff(min, unit, true);
    },
    startOf: function (time, unit, weekday) {
        return time.startOf(unit, weekday);
    },
    endOf: function (time, unit) {
        return time.endOf(unit);
    },
});
