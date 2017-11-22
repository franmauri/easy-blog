let appUrl = document.location.origin

let PassportConfig = {
  clientId: 2,
  clientSecret: 'EpOVqJMTQdgDYXSGUlhBZOqxYuDUWWLp4sdIjUdL'
}

let Config = {
  baseUrl: appUrl + '/api/',
  apiTokenLogin: appUrl + '/oauth/token',
  WeatherKey: '47f30b0313ae4381b7d21e7a6c6c26f0'
}

export {PassportConfig, Config, appUrl}
