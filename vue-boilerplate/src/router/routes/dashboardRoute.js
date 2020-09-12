import store from "@/store"

function loadDashboard(view) {
    return () => import( /* webpackChunkName: "dashboard-[request]" */ `@/components/dashboard/${view}.vue`)
}

const dashboardRoute = [
    {
        path: '',
        name: 'Dashboard',
        meta: {
          requiresAuth: true,
          roleVerif: false,
          role: []
        },
        component: loadDashboard('main'),
    },
    {
        path: '/zoom/:id/:pwd',
        name: 'Zoom Data',
        meta: {
          requiresAuth: true,
          roleVerif: false,
          role: []
        },
        component: loadDashboard('zoommeeting'),
    }
]
const routerFilter = async () => {
  await store.restored;
  const myrole = store.getters["auth/getRole"];
  if(myrole){
    return dashboardRoute.filter((row) => {
      if(row.meta.requiresAuth){
        if(row.meta.roleVerif){
          return row.meta.role.filter(role => {
            return role == myrole
          }).length > 0
        }else{
          return true
        }
      }else{
        return true
      }
    })
  }else{
    return dashboardRoute
  }
}
export default routerFilter()