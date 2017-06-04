const queryString = require('query-string')
const store = require('store')

const languages = ['en', 'zh-TW']
const parsed = queryString.parse(location.search)

let language = 'zh-TW'

if (parsed.lng && languages.includes(parsed.lng)) {
  language = parsed.lng
} else if (store.get('lng') && languages.includes(store.get('lng'))) {
  language = store.get('lng')
} else {
  for (let lan of navigator.languages) {
    if (languages.includes(lan)) {
      language = lan

      break
    }
  }
}

store.set('lng', language)

module.exports = require(`./data-table/${language}.json`)
