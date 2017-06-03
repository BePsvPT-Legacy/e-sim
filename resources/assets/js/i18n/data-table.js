const languages = ['en-US', 'zh-TW']

let language = 'zh-TW'

for (let lan of navigator.languages) {
  if (languages.includes(lan)) {
    language = lan

    break
  }
}

module.exports = require(`./data-table/${language}.json`)
