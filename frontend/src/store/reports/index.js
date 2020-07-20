import ModuleMaker from 'vuex-module-maker';
import axios from 'axios';
import $const from '../../constants';

const template = {
  instructions: {
    status: 'string',
    reports: {
      type: 'array',
      // initial_value: JSON.parse(localStorage.getItem('reports')) || [],
      initial_value: [],
    },
  },
  actions: {
    fetchReports: async ({ commit, rootState }) => {
      const { authModule: { token } } = rootState;
      const endpoint = `${$const.API.BASE_URL}${$const.API.ENDPOINTS.FETCH_REPORTS}`;
      const config = { headers: { Authorization: `Bearer ${token}` } };
      try {
        commit('setStatus', $const.API.STATUS.LOADING);
        const { data: { data: reports } } = await axios.get(endpoint, config);
        localStorage.setItem('reports', JSON.stringify(reports));
        commit('setReports', reports);
        commit('setStatus', $const.API.STATUS.SUCCESS);
      } catch (error) {
        commit('setStatus', $const.API.STATUS.ERROR);
        if (error.response) {
          if (error.response.status === 401) {
            commit('setStatus', $const.API.STATUS.UNAUTHORIZED);
          }
        }
      }
    },
  },
};

const config = {
  namespaced: true,
};

export default ModuleMaker.Make(template, config);
