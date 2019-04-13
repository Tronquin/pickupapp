export default class Gate {
    constructor(user) {
        this.user = user;
    }

    isAdmin() {
        return this.user.type === 'admin';
    }

    isDeveloper() {
        return this.user.type === 'dev';
    }

    isEmployee() {
        return this.user.type === 'employee';
    }

    isDelivery() {
        return this.user.type === 'delivery';
    }

    isUser() {
        return this.user.type === 'user';
    }

    isAdminOrDev() {
        if (this.user.type === 'admin' || this.user.type === 'dev') {
            return true;
        }
    }
}