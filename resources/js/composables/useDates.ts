import dayjs from 'dayjs'
import 'dayjs/locale/es-mx'
import utc from 'dayjs/plugin/utc'
import timezone from 'dayjs/plugin/timezone'

dayjs.extend(timezone)
dayjs.locale('es-mx');
dayjs.extend(utc)

export function useDates() {
  const currentTimezone = dayjs.tz.guess()
  
  const formatDate = (dateString: string) => {
    return dayjs(dateString).utc().format('D MMM YYYY');
  };

  const formatDateTime = (dateString: string) => {
    return dayjs(dateString).utc().format('D MMM YYYY, h:mm A');
  }

  const daysRemaining = (endDate: string | Date): number => {
    const now = dayjs().utc();
    const end = dayjs(endDate).utc();
    if (now.isAfter(end)) {
      return 0; // La fecha ya ha pasado
    }
    return end.diff(now, 'day');
  }

  const now = () => dayjs().utc();
  const nowLocal = () => dayjs().local();

  const onlyHoursAndMinutes = (dateString: string) => {
    return dayjs(dateString).utc().format('HH:mm A');
  }

  const customParseFormat = (dateString: string, format: string) => {
    return dayjs(dateString).utc().format(format);
  }

  const transformTimeZone = (dateString: string) => {
    return dayjs(dateString).tz(currentTimezone).format();
  };

  const isMoreThanOneHour = (startDate: string | Date): boolean => {
  const start = dayjs(startDate).utc();
  return start.diff(nowLocal(), 'hour', true) > 1;
}

  return {
    formatDate,
    formatDateTime,
    daysRemaining,
    onlyHoursAndMinutes,
    now,
    customParseFormat,
    transformTimeZone,
    nowLocal,
    isMoreThanOneHour,
  };
}
