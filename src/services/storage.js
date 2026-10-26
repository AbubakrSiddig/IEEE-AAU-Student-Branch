// Storage service with initial seed data matching wireframe mockup
const STORAGE_KEYS = {
  APPLICATIONS: 'ieee_aau_applications',
  MEMBERS: 'ieee_aau_members',
  VOLUNTEERS: 'ieee_aau_volunteers',
  EVENTS: 'ieee_aau_events',
  NEWS: 'ieee_aau_news',
  ARTICLES: 'ieee_aau_articles',
  MAGAZINES: 'ieee_aau_magazines',
  TERMS: 'ieee_aau_terms',
  ACTIVITIES: 'ieee_aau_activities',
  LANG: 'ieee_aau_lang',
};

const initialData = {
  applications: [
    {
      id: "#001",
      name: "Ahmed Mohamed",
      email: "ahmed@aau.edu",
      phone: "+249 912 345 678",
      major: "CS",
      college: "Computer Science & IT",
      year: "3rd",
      status: "Pending",
      appliedOn: "Apr 13, 2025",
      termsVersion: "v2.0",
      termsAcceptedAt: "2025-04-13 10:24",
      notes: "Interested in Tech & Web Development committee."
    },
    {
      id: "#002",
      name: "Sara Ali",
      email: "sara@aau.edu",
      phone: "+249 911 234 567",
      major: "EE",
      college: "Engineering",
      year: "2nd",
      status: "Accepted",
      appliedOn: "Apr 11, 2025",
      termsVersion: "v2.0",
      termsAcceptedAt: "2025-04-11 14:15",
      notes: "Selected for Events coordination team."
    },
    {
      id: "#003",
      name: "Mohamed Hassan",
      email: "m.hassan@aau.edu",
      phone: "+249 918 765 432",
      major: "CS",
      college: "Computer Science & IT",
      year: "4th",
      status: "Rejected",
      appliedOn: "Apr 10, 2025",
      termsVersion: "v2.0",
      termsAcceptedAt: "2025-04-10 09:30",
      notes: "Graduating semester, recommended for Alumni network."
    },
    {
      id: "#004",
      name: "Fatima Ibrahim",
      email: "fatima@aau.edu",
      phone: "+249 922 456 789",
      major: "CS",
      college: "Computer Science & IT",
      year: "2nd",
      status: "Pending",
      appliedOn: "Apr 8, 2025",
      termsVersion: "v2.0",
      termsAcceptedAt: "2025-04-08 16:42",
      notes: "Strong portfolio in graphic design and branding."
    },
    {
      id: "#005",
      name: "Omer Khalid",
      email: "omer.k@aau.edu",
      phone: "+249 915 678 123",
      major: "EE",
      college: "Engineering",
      year: "1st",
      status: "Accepted",
      appliedOn: "Apr 6, 2025",
      termsVersion: "v2.0",
      termsAcceptedAt: "2025-04-06 11:10",
      notes: "Enthusiastic robotics and embedded systems applicant."
    }
  ],
  members: [
    {
      id: "#001",
      name: "Ahmed Mohamed",
      email: "ahmed@aau.edu",
      phone: "+249 912 345 678",
      team: "Media",
      year: "3rd",
      role: "Media Officer",
      status: "Active",
      joinDate: "2024-10-15"
    },
    {
      id: "#002",
      name: "Sara Ali",
      email: "sara@aau.edu",
      phone: "+249 911 234 567",
      team: "Events",
      year: "2nd",
      role: "Event Organizer",
      status: "Active",
      joinDate: "2024-11-01"
    },
    {
      id: "#003",
      name: "Mohamed Hassan",
      email: "m.hassan@aau.edu",
      phone: "+249 918 765 432",
      team: "Tech",
      year: "4th",
      role: "Lead Developer",
      status: "Active",
      joinDate: "2023-09-10"
    },
    {
      id: "#004",
      name: "Fatima Ibrahim",
      email: "fatima@aau.edu",
      phone: "+249 922 456 789",
      team: "Design",
      year: "2nd",
      role: "Graphic Designer",
      status: "Active",
      joinDate: "2024-12-05"
    },
    {
      id: "#005",
      name: "Tariq Mansoor",
      email: "tariq.m@aau.edu",
      phone: "+249 913 888 999",
      team: "PR",
      year: "3rd",
      role: "PR Coordinator",
      status: "Active",
      joinDate: "2024-01-20"
    }
  ],
  volunteers: [
    {
      id: "V-101",
      name: "Hiba Babiker",
      email: "hiba.b@aau.edu",
      phone: "+249 901 123 456",
      team: "Events",
      activities: ["Events & Logistics", "Media & Coverage"],
      status: "Accepted",
      appliedOn: "Apr 14, 2025"
    },
    {
      id: "V-102",
      name: "Youssef Nader",
      email: "youssef.n@aau.edu",
      phone: "+249 902 234 567",
      team: "Design",
      activities: ["Design & Branding"],
      status: "Pending",
      appliedOn: "Apr 12, 2025"
    },
    {
      id: "V-103",
      name: "Rania Saeed",
      email: "rania.s@aau.edu",
      phone: "+249 903 345 678",
      team: "Media",
      activities: ["Media & Coverage", "Technical & Coding"],
      status: "Accepted",
      appliedOn: "Apr 09, 2025"
    }
  ],
  events: [
    {
      id: "EV-01",
      title: "AI & Future Technologies",
      date: "May 15, 2025",
      isoDate: "2025-05-15T10:00:00",
      time: "10:00 AM",
      location: "AAU Main Hall",
      status: "Upcoming",
      description: "A premier conference delving into modern Generative AI, Robotics, and industry applications.",
      speakers: "Dr. Al-Amin & Eng. Maryam",
      seats: 150
    },
    {
      id: "EV-02",
      title: "Web Development Workshop",
      date: "Apr 25, 2025",
      isoDate: "2025-04-25T09:00:00",
      time: "09:00 AM",
      location: "Lab 2 - CS Dept",
      status: "Upcoming",
      description: "Hands-on bootcamp building fullstack modern web applications with Vue and APIs.",
      speakers: "Mohamed Hassan (Tech Lead)",
      seats: 40
    },
    {
      id: "EV-03",
      title: "IEEE Day Celebration",
      date: "May 2, 2025",
      isoDate: "2025-05-02T16:00:00",
      time: "04:00 PM",
      location: "Campus Garden",
      status: "Upcoming",
      description: "Celebrating global IEEE achievements, networking, interactive quizzes, and project pitches.",
      speakers: "Branch Counselor & Guest Alumni",
      seats: 200
    },
    {
      id: "EV-04",
      title: "Renewable Energy Talk",
      date: "May 20, 2025",
      isoDate: "2025-05-20T11:00:00",
      time: "11:00 AM",
      location: "Main Hall",
      status: "Upcoming",
      description: "Solar microgrids and energy storage engineering innovations in developing nations.",
      speakers: "Prof. Dr. Osman",
      seats: 100
    }
  ],
  news: [
    {
      id: 1,
      title: "IEEE AAU Organizes a Technical Workshop",
      category: "Events",
      date: "Apr 12, 2025",
      snippet: "A successful workshop on Embedded Systems and IoT architectures for engineering undergraduates.",
      image: "https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=600&q=80",
      author: "Media Team"
    },
    {
      id: 2,
      title: "New IEEE Magazine Issue Released",
      category: "Announcements",
      date: "Apr 5, 2025",
      snippet: "Explore the latest issue of IEEE AAU Magazine featuring student innovations, AI ethics, and interviews.",
      image: "https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=600&q=80",
      author: "Editorial Committee"
    },
    {
      id: 3,
      title: "Upcoming Event: AI & Future Technologies",
      category: "Events",
      date: "Mar 28, 2025",
      snippet: "Register now for our flagship symposium featuring guest keynote speakers from regional tech firms.",
      image: "https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=600&q=80",
      author: "Events Team"
    },
    {
      id: 4,
      title: "IEEE AAU Participates in IEEE Region 8 Event",
      category: "Achievements",
      date: "Mar 20, 2025",
      snippet: "Our branch represented Alzaeim Alazhari University at the annual cross-sectional congress.",
      image: "https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=600&q=80",
      author: "Branch Chair"
    },
    {
      id: 5,
      title: "Call for Volunteers for Upcoming Event",
      category: "Announcements",
      date: "Mar 15, 2025",
      snippet: "Be part of the dynamic execution team and build unforgettable team leadership experience.",
      image: "https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=600&q=80",
      author: "HR Coordinator"
    }
  ],
  articles: [
    {
      id: 1,
      title: "The Future of AI in Education",
      category: "Technology",
      date: "Apr 10, 2025",
      author: "Sara Ali",
      snippet: "How artificial intelligence is transforming adaptive learning and digital curriculum design.",
      image: "https://images.unsplash.com/photo-1620712943543-bcc4688e7485?auto=format&fit=crop&w=600&q=80"
    },
    {
      id: 2,
      title: "Getting Started with Embedded Systems",
      category: "Technology",
      date: "Apr 02, 2025",
      author: "Ahmed Mohamed",
      snippet: "A practical guide for engineering students stepping into microcontrollers, ARM, and real-time OS.",
      image: "https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=600&q=80"
    },
    {
      id: 3,
      title: "Why IEEE Membership Matters",
      category: "IEEE",
      date: "Mar 25, 2025",
      author: "Branch Chair",
      snippet: "Unlocking access to IEEE Xplore digital library, global grant competitions, and international conferences.",
      image: "https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=600&q=80"
    },
    {
      id: 4,
      title: "Tips for a Successful Engineering Career",
      category: "Career",
      date: "Feb 18, 2025",
      author: "Eng. Maryam",
      snippet: "Essential soft skills, professional networking strategies, and open-source portfolio development.",
      image: "https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&w=600&q=80"
    }
  ],
  magazines: {
    latest: {
      volume: "Vol. 12 | Issue 3 | 2025",
      title: "Engineering a Smarter Tomorrow",
      cover: "https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=600&q=80",
      description: "Dive into cutting-edge breakthroughs in renewable grid integration, quantum computing fundamentals, and student innovations from AAU.",
      downloadUrl: "#",
      publishedDate: "April 2025"
    },
    past: [
      { id: 1, volume: "Vol. 12 Issue 1", year: "2025", cover: "https://images.unsplash.com/photo-1634017839464-5c339ebe3cb4?auto=format&fit=crop&w=600&q=80" },
      { id: 2, volume: "Vol. 11 Issue 4", year: "2024", cover: "https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=600&q=80" },
      { id: 3, volume: "Vol. 11 Issue 3", year: "2024", cover: "https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&w=600&q=80" },
      { id: 4, volume: "Vol. 11 Issue 2", year: "2024", cover: "https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?auto=format&fit=crop&w=600&q=80" }
    ]
  },
  terms: {
    versions: [
      {
        version: "v2.0",
        title: "IEEE AAU SB Terms & Bylaws 2025",
        effectiveDate: "Apr 1, 2025",
        status: "Active",
        content: `IEEE AAU Student Branch - Terms and Conditions (v2.0)

1. Introduction
These terms and conditions govern your membership, participation, and volunteering in IEEE AAU Student Branch (Alzaeim Alazhari University) activities, events, and programs.

2. Membership and Participation
By submitting your application, you agree to abide by the IEEE Code of Ethics, university student regulations, and IEEE AAU SB internal bylaws. Members are expected to actively participate in committee initiatives and branch meetings.

3. Code of Conduct & Respect
- Mutual respect, professional integrity, and teamwork are required across all committees.
- Harassment, discrimination, or divisive behavior will result in immediate termination of membership.

4. Confidentiality & Intellectual Property
- Branch organizational assets, media materials, and internal records must be kept confidential.
- Official representations and statements on behalf of the branch must receive prior clearance from the Branch Executive Committee.

5. Volunteer Obligations & Rights
- Volunteers are expected to execute their assigned tasks diligently and adhere to committed timelines.
- Academic commitments take precedence: branch activities must never cause students to miss scheduled university lectures or exams.
- Active volunteers in good standing are entitled to official verified certificates of appreciation and leadership recognition.`
      },
      {
        version: "v1.0",
        title: "IEEE AAU SB Terms & Bylaws 2024",
        effectiveDate: "Jan 1, 2024",
        status: "Archived",
        content: `IEEE AAU Student Branch - Terms and Conditions (v1.0)
Legacy rules and branch operating guidelines for 2024 academic year.`
      }
    ]
  },
  activities: [
    { date: "Apr 13, 2025", action: "New Application", user: "Ahmed Mohamed", details: "Member Application (#001)" },
    { date: "Apr 11, 2025", action: "Event Created", user: "Sara Ali", details: "AI & Future Technologies" },
    { date: "Apr 10, 2025", action: "New Volunteer", user: "Mohamed Hassan", details: "Media Team (V-103)" },
    { date: "Apr 08, 2025", action: "Terms Updated", user: "Branch Chair", details: "Version 2.0 published" },
    { date: "Apr 05, 2025", action: "Magazine Published", user: "Editor", details: "Engineering a Smarter Tomorrow" }
  ]
};

// Storage helper functions
export const storageService = {
  get(key, defaultValue) {
    try {
      const stored = localStorage.getItem(key);
      return stored ? JSON.parse(stored) : defaultValue;
    } catch {
      return defaultValue;
    }
  },
  set(key, value) {
    try {
      localStorage.setItem(key, JSON.stringify(value));
    } catch (e) {
      console.error("Storage error:", e);
    }
  },

  // Initialize all storage with seeds if not present
  init() {
    if (!localStorage.getItem(STORAGE_KEYS.APPLICATIONS)) {
      this.set(STORAGE_KEYS.APPLICATIONS, initialData.applications);
    }
    if (!localStorage.getItem(STORAGE_KEYS.MEMBERS)) {
      this.set(STORAGE_KEYS.MEMBERS, initialData.members);
    }
    if (!localStorage.getItem(STORAGE_KEYS.VOLUNTEERS)) {
      this.set(STORAGE_KEYS.VOLUNTEERS, initialData.volunteers);
    }
    if (!localStorage.getItem(STORAGE_KEYS.EVENTS)) {
      this.set(STORAGE_KEYS.EVENTS, initialData.events);
    }
    if (!localStorage.getItem(STORAGE_KEYS.NEWS)) {
      this.set(STORAGE_KEYS.NEWS, initialData.news);
    }
    if (!localStorage.getItem(STORAGE_KEYS.ARTICLES)) {
      this.set(STORAGE_KEYS.ARTICLES, initialData.articles);
    }
    if (!localStorage.getItem(STORAGE_KEYS.MAGAZINES)) {
      this.set(STORAGE_KEYS.MAGAZINES, initialData.magazines);
    }
    if (!localStorage.getItem(STORAGE_KEYS.TERMS)) {
      this.set(STORAGE_KEYS.TERMS, initialData.terms);
    }
    if (!localStorage.getItem(STORAGE_KEYS.ACTIVITIES)) {
      this.set(STORAGE_KEYS.ACTIVITIES, initialData.activities);
    }
  },

  resetAll() {
    this.set(STORAGE_KEYS.APPLICATIONS, initialData.applications);
    this.set(STORAGE_KEYS.MEMBERS, initialData.members);
    this.set(STORAGE_KEYS.VOLUNTEERS, initialData.volunteers);
    this.set(STORAGE_KEYS.EVENTS, initialData.events);
    this.set(STORAGE_KEYS.NEWS, initialData.news);
    this.set(STORAGE_KEYS.ARTICLES, initialData.articles);
    this.set(STORAGE_KEYS.MAGAZINES, initialData.magazines);
    this.set(STORAGE_KEYS.TERMS, initialData.terms);
    this.set(STORAGE_KEYS.ACTIVITIES, initialData.activities);
  },

  getApplications() {
    return this.get(STORAGE_KEYS.APPLICATIONS, initialData.applications);
  },
  saveApplications(apps) {
    this.set(STORAGE_KEYS.APPLICATIONS, apps);
  },
  addApplication(app) {
    const apps = this.getApplications();
    const count = apps.length + 1;
    const newApp = {
      id: `#${String(count).padStart(3, '0')}`,
      ...app,
      appliedOn: new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
      status: 'Pending'
    };
    apps.unshift(newApp);
    this.saveApplications(apps);
    this.logActivity('New Application', newApp.name, `Member Application (${newApp.id})`);
    return newApp;
  },
  updateApplicationStatus(id, newStatus) {
    const apps = this.getApplications();
    const app = apps.find(a => a.id === id);
    if (app) {
      app.status = newStatus;
      this.saveApplications(apps);
      this.logActivity('Status Changed', 'Admin', `Application ${id} marked as ${newStatus}`);
      
      // If accepted, add to members if not already there
      if (newStatus === 'Accepted') {
        const members = this.getMembers();
        if (!members.find(m => m.email === app.email)) {
          members.unshift({
            id: `#${String(members.length + 1).padStart(3, '0')}`,
            name: app.name,
            email: app.email,
            phone: app.phone || '',
            team: app.major === 'CS' ? 'Tech' : 'Media',
            year: app.year || '1st',
            role: 'Member',
            status: 'Active',
            joinDate: new Date().toISOString().split('T')[0]
          });
          this.saveMembers(members);
        }
      }
    }
  },

  getMembers() {
    return this.get(STORAGE_KEYS.MEMBERS, initialData.members);
  },
  saveMembers(members) {
    this.set(STORAGE_KEYS.MEMBERS, members);
  },
  addMember(member) {
    const members = this.getMembers();
    const newMember = {
      id: `#${String(members.length + 1).padStart(3, '0')}`,
      ...member,
      status: 'Active',
      joinDate: new Date().toISOString().split('T')[0]
    };
    members.unshift(newMember);
    this.saveMembers(members);
    this.logActivity('New Member Added', 'Admin', `${newMember.name} joined ${newMember.team} team`);
    return newMember;
  },

  getVolunteers() {
    return this.get(STORAGE_KEYS.VOLUNTEERS, initialData.volunteers);
  },
  saveVolunteers(volunteers) {
    this.set(STORAGE_KEYS.VOLUNTEERS, volunteers);
  },
  addVolunteer(volunteer) {
    const volunteers = this.getVolunteers();
    const newVol = {
      id: `V-${100 + volunteers.length + 1}`,
      ...volunteer,
      status: 'Pending',
      appliedOn: new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
    };
    volunteers.unshift(newVol);
    this.saveVolunteers(volunteers);
    this.logActivity('New Volunteer', newVol.name, `${newVol.team} Team (${newVol.id})`);
    return newVol;
  },

  getEvents() {
    return this.get(STORAGE_KEYS.EVENTS, initialData.events);
  },
  saveEvents(events) {
    this.set(STORAGE_KEYS.EVENTS, events);
  },
  addEvent(event) {
    const events = this.getEvents();
    const newEvent = {
      id: `EV-${String(events.length + 1).padStart(2, '0')}`,
      ...event,
      status: 'Upcoming'
    };
    events.unshift(newEvent);
    this.saveEvents(events);
    this.logActivity('Event Created', 'Admin', newEvent.title);
    return newEvent;
  },

  getNews() {
    return this.get(STORAGE_KEYS.NEWS, initialData.news);
  },
  getArticles() {
    return this.get(STORAGE_KEYS.ARTICLES, initialData.articles);
  },
  getMagazines() {
    return this.get(STORAGE_KEYS.MAGAZINES, initialData.magazines);
  },

  getTerms() {
    return this.get(STORAGE_KEYS.TERMS, initialData.terms);
  },
  saveTerms(terms) {
    this.set(STORAGE_KEYS.TERMS, terms);
  },
  addTermsVersion(versionData) {
    const terms = this.getTerms();
    terms.versions.forEach(v => v.status = 'Archived');
    terms.versions.unshift({
      version: versionData.version,
      title: versionData.title,
      effectiveDate: new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
      status: 'Active',
      content: versionData.content
    });
    this.saveTerms(terms);
    this.logActivity('Terms Updated', 'Admin', `Version ${versionData.version} published`);
  },

  getActivities() {
    return this.get(STORAGE_KEYS.ACTIVITIES, initialData.activities);
  },
  logActivity(action, user, details) {
    const activities = this.getActivities();
    activities.unshift({
      date: new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
      action,
      user,
      details
    });
    if (activities.length > 50) activities.pop();
    this.set(STORAGE_KEYS.ACTIVITIES, activities);
  }
};
