// Authentication utilities for admin components
export const AUTH_TOKEN_KEY = 'token' // Match the key used in AdminLogin.vue

export const getAuthToken = (): string | null => {
    const token = localStorage.getItem(AUTH_TOKEN_KEY)
    console.log('Retrieved auth token:', token ? 'Token found' : 'No token found')
    return token
}

export const setAuthToken = (token: string): void => {
    localStorage.setItem(AUTH_TOKEN_KEY, token)
}

export const clearAuthToken = (): void => {
    localStorage.removeItem(AUTH_TOKEN_KEY)
}

export const isAuthenticated = (): boolean => {
    return !!getAuthToken()
}

export const getAuthHeaders = (): Record<string, string> => {
    const token = getAuthToken()
    const headers: Record<string, string> = {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
    
    if (token) {
        headers['Authorization'] = `Bearer ${token}`
    }
    
    return headers
}

// Handle common authentication errors
export const handleAuthError = (status: number, redirectToLogin?: () => void): boolean => {
    if (status === 401) {
        console.error('Authentication failed - token may be expired or invalid')
        alert('Authentication failed. Please log in again.')
        clearAuthToken()
        
        if (redirectToLogin) {
            redirectToLogin()
        }
        return true
    }
    return false
}

// Check if user is authenticated and show appropriate message if not
export const requireAuth = (redirectToLogin?: () => void): boolean => {
    if (!isAuthenticated()) {
        console.warn('No authentication token found')
        alert('Authentication required. Please log in first.')
        
        if (redirectToLogin) {
            redirectToLogin()
        }
        return false
    }
    return true
}
