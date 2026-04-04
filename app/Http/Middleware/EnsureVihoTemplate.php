<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureVihoTemplate
{
    /**
     * Enforce template consistency based on the active layout_template setting.
     * - If Viho is active, redirect default routes to /ai-template/*
     * - If Default is active, redirect /ai-template/* back to default
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->shouldSkip($request)) {
            return $next($request);
        }

        $layout_template = $this->getLayoutTemplate($request);
        $is_viho_active = $layout_template === 'viho';
        $is_ai_route = $request->segment(1) === 'ai-template';

        if ($is_viho_active && ! $is_ai_route) {
            return redirect()->to($this->buildAiTemplateUrl($request));
        }

        if (! $is_viho_active && $is_ai_route) {
            return redirect()->to($this->stripAiTemplateUrl($request));
        }

        return $next($request);
    }

    private function shouldSkip(Request $request): bool
    {
        if (! $request->isMethod('GET') && ! $request->isMethod('HEAD')) {
            return true;
        }

        if ($request->ajax() || $request->expectsJson() || $request->wantsJson()) {
            return true;
        }

        return false;
    }

    private function getLayoutTemplate(Request $request): string
    {
        $business = $request->session()->get('business');
        $common_settings = [];

        if (is_object($business) && isset($business->common_settings)) {
            $common_settings = $business->common_settings;
        } elseif (is_array($business) && isset($business['common_settings'])) {
            $common_settings = $business['common_settings'];
        }

        if (is_string($common_settings)) {
            $common_settings = json_decode($common_settings, true) ?? [];
        }

        $layout_template = $common_settings['layout_template'] ?? 'default';

        return is_string($layout_template) ? strtolower($layout_template) : 'default';
    }

    private function buildAiTemplateUrl(Request $request): string
    {
        $path = trim($request->path(), '/');
        $target_path = $path === '' ? 'ai-template/home' : 'ai-template/' . $path;
        $target_url = url($target_path);

        $query = $request->getQueryString();
        if (! empty($query)) {
            $target_url .= '?' . $query;
        }

        return $target_url;
    }

    private function stripAiTemplateUrl(Request $request): string
    {
        $path = trim($request->path(), '/');
        $path = preg_replace('#^ai-template/?#', '', $path);
        $path = trim($path ?? '', '/');
        $target_path = $path === '' ? 'home' : $path;
        $target_url = url($target_path);

        $query = $request->getQueryString();
        if (! empty($query)) {
            $target_url .= '?' . $query;
        }

        return $target_url;
    }
}
