export default {
  name(name) {
    if (!name) {
      return ''
    }

    let nameSplited = name.split(' ')

    if (nameSplited.length === 1) {
      return nameSplited[0]
    }

    return nameSplited[0] + ' ' + nameSplited[nameSplited.length - 1] ?? ''
  },
  date(date) {
    if (!date) {
      return '-'
    }

    let dateSplited = date.split('-')

    return dateSplited[2] + '/' + dateSplited[1] + '/' + dateSplited[0]
  },
  timestamp(timestamp, time = false) {
    const date = new Date(timestamp)
    const day = String(date.getDate()).padStart(2, '0')
    const month = String(date.getMonth() + 1).padStart(2, '0')
    const year = date.getFullYear()

    if (time) {
      const hours = String(date.getHours()).padStart(2, '0')
      const minutes = String(date.getMinutes()).padStart(2, '0')
      return `${day}/${month}/${year} ${hours}:${minutes}`
    }

    return `${day}/${month}/${year}`
  },
  title(title) {
    if (!title) {
      return ''
    }

    return title.replace(/\w\S*/g, function (word) {
      return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()
    })
  }
}
