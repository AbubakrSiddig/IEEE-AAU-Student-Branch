import { createRouter, createWebHashHistory } from 'vue-router';

// Public views
import Home from '../views/public/Home.vue';
import About from '../views/public/About.vue';
import OurTeam from '../views/public/OurTeam.vue';
import News from '../views/public/News.vue';
import Events from '../views/public/Events.vue';
import Articles from '../views/public/Articles.vue';
import Magazine from '../views/public/Magazine.vue';
import Volunteer from '../views/public/Volunteer.vue';
import Contact from '../views/public/Contact.vue';
import JoinWizard from '../views/public/JoinWizard.vue';
import TrackApplication from '../views/public/TrackApplication.vue';

// Auth & User Account Views (CR v0.3.1 Section 2)
import Login from '../views/public/Login.vue';
import Register from '../views/public/Register.vue';
import Profile from '../views/public/Profile.vue';
import MyEvents from '../views/public/MyEvents.vue';
import MyApplications from '../views/public/MyApplications.vue';

// Admin views
import AdminLayout from '../views/admin/AdminLayout.vue';
import Dashboard from '../views/admin/Dashboard.vue';
import Applications from '../views/admin/Applications.vue';
import Members from '../views/admin/Members.vue';
import VolunteersManage from '../views/admin/VolunteersManage.vue';
import EventsManage from '../views/admin/EventsManage.vue';
import MagazineManage from '../views/admin/MagazineManage.vue';
import MediaManage from '../views/admin/MediaManage.vue';
import Reports from '../views/admin/Reports.vue';
import TermsManage from '../views/admin/TermsManage.vue';
import OfficeTermsManage from '../views/admin/OfficeTermsManage.vue';
import Settings from '../views/admin/Settings.vue';

const routes = [
  // Public
  { path: '/', name: 'Home', component: Home },
  { path: '/about', name: 'About', component: About },
  { path: '/team', name: 'OurTeam', component: OurTeam },
  { path: '/team/:termId', name: 'OurTeamArchive', component: OurTeam },
  { path: '/news', name: 'News', component: News },
  { path: '/events', name: 'Events', component: Events },
  { path: '/articles', name: 'Articles', component: Articles },
  { path: '/magazine', name: 'Magazine', component: Magazine },
  { path: '/volunteer', name: 'Volunteer', component: Volunteer },
  { path: '/contact', name: 'Contact', component: Contact },
  { path: '/join', name: 'JoinWizard', component: JoinWizard },
  { path: '/track-application', name: 'TrackApplication', component: TrackApplication },

  // User Accounts & Authentication (CR v0.3.1)
  { path: '/login', name: 'Login', component: Login },
  { path: '/register', name: 'Register', component: Register },
  { path: '/profile', name: 'Profile', component: Profile },
  { path: '/my-events', name: 'MyEvents', component: MyEvents },
  { path: '/my-applications', name: 'MyApplications', component: MyApplications },

  // Admin Portal (with RBAC Protection)
  {
    path: '/admin',
    component: AdminLayout,
    children: [
      { path: '', name: 'AdminDashboard', component: Dashboard },
      { path: 'applications', name: 'AdminApplications', component: Applications },
      { path: 'members', name: 'AdminMembers', component: Members },
      { path: 'volunteers', name: 'AdminVolunteers', component: VolunteersManage },
      { path: 'magazines', name: 'AdminMagazines', component: MagazineManage },
      { path: 'media', name: 'AdminMedia', component: MediaManage },
      { path: 'events', name: 'AdminEvents', component: EventsManage },
      { path: 'reports', name: 'AdminReports', component: Reports },
      { path: 'terms', name: 'AdminTerms', component: TermsManage },
      { path: 'office-terms', name: 'AdminOfficeTerms', component: OfficeTermsManage },
      { path: 'settings', name: 'AdminSettings', component: Settings },
    ]
  },

  // Fallback
  { path: '/:pathMatch(.*)*', redirect: '/' }
];

const router = createRouter({
  history: createWebHashHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition;
    } else {
      return { top: 0, behavior: 'smooth' };
    }
  }
});

export default router;
