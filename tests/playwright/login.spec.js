import { test, expect } from '@playwright/test';

test('login-success', async ({ page }) => {
  await page.goto('http://localhost:8000/login');
  await page.getByPlaceholder('Email').click();
  await page.getByPlaceholder('Email').fill('tim@nowhere.com');
  await page.getByPlaceholder('Password').click();
  await page.getByPlaceholder('Password').fill('timtimtim');
  const responsePromise = page.waitForResponse('http://localhost:8000/login');
  await page.getByRole('button', { name: 'Submit' }).click()
  const response = await responsePromise;
  expect(response.status()).toBe(302);
  expect(response.headers().location).toBe('http://localhost:8000/home');
  await page.getByRole('button', { name: 'Logout' }).click();
});

test('login-failure-wrong-username-password', async ({ page }) => {
  await page.goto('http://localhost:8000/login');
  await page.getByPlaceholder('Email').click();
  await page.getByPlaceholder('Email').fill('woops@ddj.com');
  await page.getByPlaceholder('Password').click();
  await page.getByPlaceholder('Password').fill('wqeqwjen');
  const responsePromise = page.waitForResponse('http://localhost:8000/login');
  await page.getByRole('button', { name: 'Submit' }).click()
  const response = await responsePromise;
  expect(response.status()).toBe(302);
  expect(response.headers().location).toBe('http://localhost:8000/login');
});
