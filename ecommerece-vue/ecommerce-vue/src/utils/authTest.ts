// Test utilities for authentication
// This file helps you test the admin authentication system

import { setAuthToken, getAuthToken, clearAuthToken } from './auth'

// Helper function to set a test token for development/testing
export const setTestAuthToken = (token: string) => {
    console.log('Setting test authentication token...')
    setAuthToken(token)
    console.log('Token set successfully. You can now access admin features.')
}

// Helper to clear test tokens
export const clearTestAuthToken = () => {
    console.log('Clearing authentication token...')
    clearAuthToken()
    console.log('Token cleared. You will need to authenticate again.')
}

// Test if current token is valid by making a test API call
export const testAuthToken = async () => {
    const token = getAuthToken()
    
    if (!token) {
        console.log('No token found. Please set a token first.')
        return false
    }
    
    try {
        const response = await fetch('http://127.0.0.1:8000/api/product_category/', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        })
        
        if (response.ok) {
            console.log('✅ Token is valid! API test successful.')
            return true
        } else {
            console.log('❌ Token test failed:', response.status, response.statusText)
            return false
        }
    } catch (error) {
        console.log('❌ Token test error:', error)
        return false
    }
}

// Print current authentication status
export const checkAuthStatus = () => {
    const token = getAuthToken()
    
    if (token) {
        console.log('🔑 Authentication token found:', token.substring(0, 20) + '...')
        console.log('You are ready to use admin features.')
    } else {
        console.log('❌ No authentication token found.')
        console.log('To test manually, use: setTestAuthToken("your_bearer_token_here")')
    }
}

// Make these functions available globally for testing in browser console
if (typeof window !== 'undefined') {
    (window as any).authTest = {
        setTestAuthToken,
        clearTestAuthToken,
        testAuthToken,
        checkAuthStatus
    }
    
    console.log('🧪 Auth test utilities loaded! Available as: authTest.setTestAuthToken(), authTest.testAuthToken(), etc.')
}
