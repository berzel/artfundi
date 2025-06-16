import axios from 'axios'

const api = axios.create({
    baseURL: 'http://localhost',
    withCredentials: true,
    withXSRFToken: true,
})

export default api