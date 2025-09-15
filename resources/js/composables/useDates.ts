import dayjs from 'dayjs'
import 'dayjs/locale/es-mx'
dayjs.locale('es-mx');

export function useDates() {
  const formatDate = (dateString: string) => {
    return dayjs(dateString).format('D MMM YYYY');
  };

  const formatDateTime = (dateString: string) => {
    return dayjs(dateString).format('D MMM YYYY, h:mm A');
  }

  return {
    formatDate,
    formatDateTime
  };
}
